<x-layouts.app title="Laporan Inventaris">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Laporan Inventaris Saat Ini</h1>
                <p class="text-sm text-neutral-500 mt-0.5">Ringkasan total kuantitas dan valuasi aset persediaan barang.</p>
            </div>
            <a href="{{ route('reports.inventory.export', request()->query()) }}" class="btn-secondary !py-2 !px-3.5">
                <i data-lucide="download" class="w-4 h-4"></i>
                Ekspor Inventaris (CSV)
            </a>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="card p-5">
                <span class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Total Jenis Produk</span>
                <p class="text-2xl font-bold text-neutral-900 mt-1">{{ number_format($products->total(), 0, ',', '.') }}</p>
                <span class="text-xs text-neutral-500 mt-0.5 block">Sesuai filter aktif</span>
            </div>
            <div class="card p-5">
                <span class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Total Kuantitas Fisik</span>
                <p class="text-2xl font-bold text-neutral-900 mt-1">{{ number_format($totalItems, 0, ',', '.') }}</p>
                <span class="text-xs text-neutral-500 mt-0.5 block">Akumulasi seluruh unit stok</span>
            </div>
            <div class="card p-5">
                <span class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Total Nilai Aset Stok (HPP)</span>
                <p class="text-2xl font-bold text-[#2D6A4F] mt-1">Rp {{ number_format($totalValue, 0, ',', '.') }}</p>
                <span class="text-xs text-neutral-500 mt-0.5 block">Dihitung berdasarkan harga beli terkini</span>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card p-4">
            <form method="GET" action="{{ route('reports.inventory') }}" class="flex flex-wrap gap-3 items-end">
                <div class="w-48">
                    <label class="form-label text-xs">Filter Kategori</label>
                    <select name="category_id" class="form-input text-xs">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-48">
                    <label class="form-label text-xs">Status Ketersediaan</label>
                    <select name="status" class="form-input text-xs">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Stok Menipis</option>
                        <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Habis (Kosong)</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="btn-primary !text-xs !py-2">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                        Filter
                    </button>
                    @if(request()->hasAny(['category_id', 'status']))
                        <a href="{{ route('reports.inventory') }}" class="btn-secondary !p-2 text-neutral-500" title="Reset filter">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="card overflow-hidden">
            <div class="table-container border-0 rounded-none">
                <table class="table">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Supplier</th>
                            <th>Stok Fisik</th>
                            <th>Min. Stok</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Nilai Total Aset</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $p)
                            <tr>
                                <td class="font-mono text-xs font-semibold text-neutral-800">{{ $p->sku }}</td>
                                <td class="font-medium text-neutral-900">
                                    <a href="{{ route('products.show', $p) }}" class="hover:text-[#2D6A4F] hover:underline">
                                        {{ $p->name }}
                                    </a>
                                </td>
                                <td class="text-xs text-neutral-600">{{ $p->category->name ?? '-' }}</td>
                                <td class="text-xs text-neutral-600">{{ $p->supplier->name ?? '-' }}</td>
                                <td class="font-semibold text-neutral-900">{{ $p->current_stock }} {{ $p->unit }}</td>
                                <td class="text-xs text-neutral-500">{{ $p->minimum_stock }} {{ $p->unit }}</td>
                                <td class="text-xs text-neutral-700">Rp {{ number_format($p->purchase_price, 0, ',', '.') }}</td>
                                <td class="text-xs text-neutral-700">Rp {{ number_format($p->selling_price, 0, ',', '.') }}</td>
                                <td class="text-xs font-bold text-neutral-900">
                                    Rp {{ number_format($p->current_stock * $p->purchase_price, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($p->stock_status === 'out_of_stock')
                                        <span class="badge-red">Habis</span>
                                    @elseif($p->stock_status === 'low_stock')
                                        <span class="badge-amber">Menipis</span>
                                    @else
                                        <span class="badge-green">Tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-10 text-neutral-400 text-sm">
                                    Tidak ada data produk yang sesuai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="p-4 border-t border-neutral-200">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
