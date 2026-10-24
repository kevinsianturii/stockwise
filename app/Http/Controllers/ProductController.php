<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier'])->active();

        // Search by name or SKU
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        // Filter by stock status
        if ($status = $request->input('status')) {
            match ($status) {
                'available'    => $query->where('current_stock', '>', \DB::raw('minimum_stock')),
                'low_stock'    => $query->where('current_stock', '>', 0)->where('current_stock', '<=', \DB::raw('minimum_stock')),
                'out_of_stock' => $query->where('current_stock', 0),
                default        => null,
            };
        }

        // Sorting
        $sortBy = $request->input('sort', 'name');
        $sortDir = $request->input('dir', 'asc');
        $allowedSorts = ['name', 'sku', 'current_stock', 'selling_price', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir === 'desc' ? 'desc' : 'asc');
        }

        $products = $query->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data['current_stock'] = 0; // Always start at zero; use stock movements

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'supplier']);
        $recentMovements = $product->stockMovements()
            ->with('user')
            ->orderByDesc('occurred_at')
            ->limit(10)
            ->get();

        return view('products.show', compact('product', 'recentMovements'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        // Never allow direct stock editing through this form
        unset($data['current_stock']);

        // Handle is_active checkbox
        $data['is_active'] = $request->boolean('is_active', true);

        $product->update($data);

        return redirect()->route('products.show', $product)
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if (!$product->canBeDeleted()) {
            return back()->with('error', 'Produk tidak dapat dihapus karena memiliki riwayat transaksi. Nonaktifkan produk sebagai gantinya.');
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
