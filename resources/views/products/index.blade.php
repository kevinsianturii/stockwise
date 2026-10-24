<x-layouts.app title="Daftar Produk">

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-neutral-800">Daftar Produk</h1>
            <p class="text-sm text-neutral-600 mt-1">Kelola semua produk dalam inventaris Anda</p>
        </div>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('products.create') }}" class="btn-primary inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Produk
            </a>
        @endif
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-green-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <p class="text-sm text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Search & Filter Form --}}
    <div class="bg-white rounded-xl border border-neutral-200 p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap gap-3 items-end">
            {{-- Text Search --}}
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="form-label">Cari Nama / SKU</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari produk..."
                    class="form-input"
                />
            </div>

            {{-- Category Filter --}}
            <div class="w-48">
                <label for="category" class="form-label">Kategori</label>
                <select id="category" name="category" class="form-input">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Stock Status Filter --}}
            <div class="w-48">
                <label for="stock_status" class="form-label">Status Stok</label>
                <select id="stock_status" name="stock_status" class="form-input">
                    <option value="">Semua</option>
                    <option value="available" {{ request('stock_status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Stok Menipis</option>
                    <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Habis</option>
                </select>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                    Cari
                </button>
                @if(request()->anyFilled(['search', 'category', 'stock_status']))
                    <a href="{{ route('products.index') }}" class="btn-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Results Count --}}
    <div class="mb-3 text-sm text-neutral-600">
        Menampilkan <span class="font-semibold text-neutral-800">{{ $products->firstItem() ?? 0 }}</span>–<span class="font-semibold text-neutral-800">{{ $products->lastItem() ?? 0 }}</span>
        dari <span class="font-semibold text-neutral-800">{{ $products->total() }}</span> produk
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-xl border border-neutral-200 shadow-sm overflow-hidden">
        @if($products->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-neutral-50 border-b border-neutral-200">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">SKU</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Nama Produk</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Kategori</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Stok</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Harga Jual</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach($products as $product)
                            @php
                                $stockColor = 'text-green-600';
                                $statusLabel = 'Tersedia';
                                $statusClass = 'bg-green-100 text-green-700';

                                if ($product->current_stock == 0) {
                                    $stockColor = 'text-red-600';
                                    $statusLabel = 'Habis';
                                    $statusClass = 'bg-red-100 text-red-700';
                                } elseif ($product->current_stock <= $product->minimum_stock) {
                                    $stockColor = 'text-amber-600';
                                    $statusLabel = 'Stok Menipis';
                                    $statusClass = 'bg-amber-100 text-amber-700';
                                }
                            @endphp
                            <tr class="hover:bg-neutral-50 transition-colors">
                                {{-- SKU --}}
                                <td class="px-4 py-3">
                                    <span class="font-mono text-xs bg-neutral-100 text-neutral-700 px-2 py-1 rounded">{{ $product->sku }}</span>
                                </td>

                                {{-- Nama Produk --}}
                                <td class="px-4 py-3">
                                    <a href="{{ route('products.show', $product) }}" class="font-medium text-neutral-800 hover:text-[#2D6A4F] transition-colors">
                                        {{ $product->name }}
                                    </a>
                                    @if(!$product->is_active)
                                        <span class="ml-1 text-xs bg-neutral-200 text-neutral-500 px-1.5 py-0.5 rounded">Nonaktif</span>
                                    @endif
                                </td>

                                {{-- Kategori --}}
                                <td class="px-4 py-3 text-neutral-600">
                                    {{ $product->category->name ?? '-' }}
                                </td>

                                {{-- Stok --}}
                                <td class="px-4 py-3 text-center">
                                    <span class="font-bold {{ $stockColor }}">{{ number_format($product->current_stock) }}</span>
                                    <span class="text-neutral-400 text-xs"> / {{ number_format($product->minimum_stock) }}</span>
                                    <div class="text-xs text-neutral-400">{{ $product->unit }}</div>
                                </td>

                                {{-- Harga Jual --}}
                                <td class="px-4 py-3 text-right font-medium text-neutral-800">
                                    Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                </td>

                                {{-- Status Badge --}}
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1">
                                        {{-- View --}}
                                        <a href="{{ route('products.show', $product) }}"
                                           class="p-1.5 text-neutral-500 hover:text-[#2D6A4F] hover:bg-green-50 rounded-lg transition-colors"
                                           title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        @if(auth()->user()->isAdmin())
                                            {{-- Edit --}}
                                            <a href="{{ route('products.edit', $product) }}"
                                               class="p-1.5 text-neutral-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                               title="Edit Produk">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>

                                            {{-- Delete --}}
                                            <div x-data>
                                                <button type="button"
                                                        @click="if(confirm('Hapus produk \'{{ addslashes($product->name) }}\'? Tindakan ini tidak dapat dibatalkan.')) $refs.deleteForm{{ $product->id }}.submit()"
                                                        class="p-1.5 text-neutral-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                        title="Hapus Produk">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                                <form x-ref="deleteForm{{ $product->id }}"
                                                      method="POST"
                                                      action="{{ route('products.destroy', $product) }}"
                                                      class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($products->hasPages())
                <div class="px-4 py-3 border-t border-neutral-200">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif

        @else
            {{-- Empty State --}}
            <div class="py-16 text-center">
                <svg class="w-16 h-16 mx-auto text-neutral-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <h3 class="text-lg font-semibold text-neutral-700 mb-1">Tidak ada produk ditemukan</h3>
                <p class="text-sm text-neutral-500 mb-4">
                    @if(request()->anyFilled(['search', 'category', 'stock_status']))
                        Tidak ada produk yang cocok dengan filter Anda.
                        <a href="{{ route('products.index') }}" class="text-[#2D6A4F] hover:underline">Reset filter</a>
                    @else
                        Belum ada produk yang ditambahkan.
                    @endif
                </p>
                @if(auth()->user()->isAdmin() && !request()->anyFilled(['search', 'category', 'stock_status']))
                    <a href="{{ route('products.create') }}" class="btn-primary">Tambah Produk Pertama</a>
                @endif
            </div>
        @endif
    </div>

</x-layouts.app>
