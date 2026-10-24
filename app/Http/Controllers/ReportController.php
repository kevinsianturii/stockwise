<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    /**
     * Current inventory report.
     */
    public function inventory(Request $request)
    {
        $query = Product::with(['category', 'supplier'])->active();

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($status = $request->input('status')) {
            match ($status) {
                'available'    => $query->where('current_stock', '>', DB::raw('minimum_stock')),
                'low_stock'    => $query->where('current_stock', '>', 0)->where('current_stock', '<=', DB::raw('minimum_stock')),
                'out_of_stock' => $query->where('current_stock', 0),
                default        => null,
            };
        }

        $products = $query->orderBy('name')->paginate(25)->withQueryString();
        $categories = Category::orderBy('name')->get();

        $totalValue = Product::active()->sum(DB::raw('current_stock * purchase_price'));
        $totalItems = Product::active()->sum('current_stock');

        return view('reports.inventory', compact('products', 'categories', 'totalValue', 'totalItems'));
    }

    /**
     * Stock movements report with date range.
     */
    public function movements(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        $query = StockMovement::with(['product', 'user'])
            ->whereDate('occurred_at', '>=', $dateFrom)
            ->whereDate('occurred_at', '<=', $dateTo);

        if ($type = $request->input('type')) {
            if (in_array($type, ['in', 'out', 'adjustment'])) {
                $query->where('type', $type);
            }
        }

        if ($categoryId = $request->input('category_id')) {
            $query->whereHas('product', fn ($q) => $q->where('category_id', $categoryId));
        }

        $movements = $query->orderByDesc('occurred_at')->paginate(25)->withQueryString();

        // Totals for the filtered range
        $totals = StockMovement::whereDate('occurred_at', '>=', $dateFrom)
            ->whereDate('occurred_at', '<=', $dateTo)
            ->selectRaw('type, COUNT(*) as count, SUM(quantity) as total_qty')
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        $categories = Category::orderBy('name')->get();

        return view('reports.movements', compact('movements', 'totals', 'dateFrom', 'dateTo', 'categories'));
    }

    /**
     * Low-stock alert report.
     */
    public function lowStock(Request $request)
    {
        $outOfStock = Product::active()
            ->with('category')
            ->where('current_stock', 0)
            ->orderBy('name')
            ->get();

        $lowStock = Product::active()
            ->with('category')
            ->where('current_stock', '>', 0)
            ->where('current_stock', '<=', DB::raw('minimum_stock'))
            ->orderBy('current_stock')
            ->get();

        return view('reports.low-stock', compact('outOfStock', 'lowStock'));
    }

    /**
     * Export inventory to CSV.
     */
    public function exportInventory(Request $request)
    {
        $query = Product::with(['category', 'supplier'])->active();

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->orderBy('name')->get();
        $filename = 'laporan-inventaris-' . now()->format('Ymd') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($products) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'SKU', 'Nama Produk', 'Kategori', 'Supplier',
                'Stok Saat Ini', 'Stok Minimum', 'Satuan',
                'Harga Beli', 'Harga Jual', 'Status',
            ]);

            foreach ($products as $p) {
                fputcsv($handle, [
                    $p->sku,
                    $p->name,
                    $p->category->name ?? '-',
                    $p->supplier->name ?? '-',
                    $p->current_stock,
                    $p->minimum_stock,
                    $p->unit,
                    number_format($p->purchase_price, 0, ',', '.'),
                    number_format($p->selling_price, 0, ',', '.'),
                    $p->stock_status_label,
                ]);
            }

            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }
}
