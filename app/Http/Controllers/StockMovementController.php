<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockInRequest;
use App\Http\Requests\StockOutRequest;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockMovementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class StockMovementController extends Controller
{
    public function __construct(
        private readonly StockMovementService $stockMovementService
    ) {}

    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'user'])->orderByDesc('occurred_at');

        // Search by reference number or product name
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('product', fn ($pq) => $pq->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter by type
        if ($type = $request->input('type')) {
            if (in_array($type, ['in', 'out', 'adjustment'])) {
                $query->where('type', $type);
            }
        }

        // Filter by date range
        if ($from = $request->input('date_from')) {
            $query->whereDate('occurred_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('occurred_at', '<=', $to);
        }

        // Filter by user
        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        $movements = $query->paginate(20)->withQueryString();
        $products = Product::active()->orderBy('name')->get();

        return view('stock-movements.index', compact('movements', 'products'));
    }

    public function create(Request $request)
    {
        $type = $request->input('type', 'in');
        if (!in_array($type, ['in', 'out'])) {
            $type = 'in';
        }

        $products = Product::active()->orderBy('name')->get();
        $selectedProduct = $request->input('product_id')
            ? Product::find($request->input('product_id'))
            : null;

        return view('stock-movements.create', compact('type', 'products', 'selectedProduct'));
    }

    public function storeIn(StockInRequest $request)
    {
        try {
            $movement = $this->stockMovementService->recordIncoming($request->validated());

            return redirect()->route('stock-movements.show', $movement)
                ->with('success', "Barang masuk berhasil dicatat. Referensi: {$movement->reference_number}");
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function storeOut(StockOutRequest $request)
    {
        try {
            $movement = $this->stockMovementService->recordOutgoing($request->validated());

            return redirect()->route('stock-movements.show', $movement)
                ->with('success', "Barang keluar berhasil dicatat. Referensi: {$movement->reference_number}");
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function show(StockMovement $stockMovement)
    {
        $stockMovement->load(['product.category', 'user']);

        return view('stock-movements.show', compact('stockMovement'));
    }

    /**
     * Export filtered stock movements to CSV.
     * Respects same filters as index.
     */
    public function export(Request $request)
    {
        $query = StockMovement::with(['product', 'user'])->orderByDesc('occurred_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('product', fn ($pq) => $pq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($type = $request->input('type')) {
            if (in_array($type, ['in', 'out', 'adjustment'])) {
                $query->where('type', $type);
            }
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('occurred_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('occurred_at', '<=', $to);
        }

        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        $movements = $query->get();

        $filename = 'transaksi-stok-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($movements) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fputs($handle, "\xEF\xBB\xBF");

            // Header row
            fputcsv($handle, [
                'No. Referensi',
                'Tanggal',
                'Produk',
                'SKU',
                'Jenis',
                'Jumlah',
                'Satuan',
                'Stok Sebelum',
                'Stok Sesudah',
                'Harga Satuan',
                'Petugas',
                'Catatan',
            ]);

            foreach ($movements as $m) {
                // Sanitize cells to prevent formula injection
                fputcsv($handle, [
                    $this->sanitizeCsvCell($m->reference_number),
                    $m->occurred_at->format('d/m/Y H:i'),
                    $this->sanitizeCsvCell($m->product->name ?? '-'),
                    $this->sanitizeCsvCell($m->product->sku ?? '-'),
                    $this->sanitizeCsvCell($m->type_label),
                    $m->quantity,
                    $this->sanitizeCsvCell($m->product->unit ?? '-'),
                    $m->stock_before,
                    $m->stock_after,
                    $m->unit_cost !== null ? number_format($m->unit_cost, 0, ',', '.') : '-',
                    $this->sanitizeCsvCell($m->user->name ?? '-'),
                    $this->sanitizeCsvCell($m->notes ?? ''),
                ]);
            }

            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Prevent spreadsheet formula injection by sanitizing CSV cell values.
     */
    private function sanitizeCsvCell(string $value): string
    {
        $dangerousChars = ['=', '+', '-', '@', "\t", "\r"];
        if (in_array(substr($value, 0, 1), $dangerousChars)) {
            return "'" . $value;
        }
        return $value;
    }
}
