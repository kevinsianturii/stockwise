<x-layouts.app title="{{ $category->name }}">

    {{-- Page Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center gap-2 text-sm text-neutral-500 mb-2">
                <a href="{{ route('categories.index') }}" class="hover:text-[#2D6A4F] transition-colors">Kategori</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-neutral-800 font-medium">{{ $category->name }}</span>
            </nav>
            <h1 class="text-2xl font-bold text-neutral-800">{{ $category->name }}</h1>
            <p class="text-sm text-neutral-500 mt-0.5 font-mono">{{ $category->slug }}</p>
        </div>
        <div class="flex gap-2">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('categories.edit', $category) }}"
                   class="btn-secondary inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Kategori
                </a>
            @endif
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Two-column Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        {{-- Category Info Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-neutral-200 shadow-sm">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-base font-semibold text-neutral-800">Informasi Kategori</h2>
                </div>
                <div class="p-6 space-y-4">

                    {{-- Name --}}
                    <div>
                        <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Nama</p>
                        <p class="text-sm font-medium text-neutral-800">{{ $category->name }}</p>
                    </div>

                    {{-- Slug --}}
                    <div>
                        <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Slug</p>
                        <span class="font-mono text-xs bg-neutral-100 text-neutral-600 px-2 py-1 rounded">{{ $category->slug }}</span>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Deskripsi</p>
                        <p class="text-sm text-neutral-700 leading-relaxed">{{ $category->description ?: '–' }}</p>
                    </div>

                    {{-- Stats --}}
                    <div class="pt-4 border-t border-neutral-100">
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm text-neutral-600">Total Produk</span>
                            <span class="font-bold text-2xl text-[#2D6A4F]">{{ $products->total() }}</span>
                        </div>
                    </div>

                    {{-- Meta --}}
                    <div class="pt-3 border-t border-neutral-100 space-y-1 text-xs text-neutral-400">
                        <div>Dibuat: {{ $category->created_at->format('d M Y, H:i') }}</div>
                        <div>Diperbarui: {{ $category->updated_at->format('d M Y, H:i') }}</div>
                    </div>

                    {{-- Admin Actions --}}
                    @if(auth()->user()->isAdmin())
                        <div class="pt-4 border-t border-neutral-100 space-y-2">
                            <a href="{{ route('categories.edit', $category) }}" class="w-full btn-secondary text-sm flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Kategori
                            </a>
                            @if($products->total() == 0)
                                <div x-data>
                                    <button type="button"
                                            @click="if(confirm('Hapus kategori \'{{ addslashes($category->name) }}\'? Tindakan ini tidak dapat dibatalkan.')) $refs.deleteCatShowForm.submit()"
                                            class="w-full btn-danger text-sm flex items-center justify-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus Kategori
                                    </button>
                                    <form x-ref="deleteCatShowForm"
                                          method="POST"
                                          action="{{ route('categories.destroy', $category) }}"
                                          class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            @else
                                <button type="button"
                                        disabled
                                        class="w-full btn-secondary text-sm flex items-center justify-center gap-1.5 opacity-50 cursor-not-allowed"
                                        title="Kategori tidak dapat dihapus karena masih memiliki produk">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2zm10-10V7a4 4 0 0 0-8 0v4h8z"/>
                                    </svg>
                                    Tidak Dapat Dihapus
                                </button>
                                <p class="text-xs text-neutral-400 text-center">Hapus atau pindahkan semua produk terlebih dahulu</p>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- Products List --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-neutral-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-200 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-neutral-800">Produk dalam Kategori Ini</h2>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('products.create') }}" class="text-sm text-[#2D6A4F] hover:underline font-medium">
                            + Tambah Produk
                        </a>
                    @endif
                </div>

                @if($products->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-neutral-50 border-b border-neutral-200">
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">SKU</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Nama Produk</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Stok</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Harga Jual</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Status</th>
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
                                        <td class="px-4 py-3">
                                            <span class="font-mono text-xs bg-neutral-100 text-neutral-700 px-2 py-1 rounded">{{ $product->sku }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('products.show', $product) }}" class="font-medium text-neutral-800 hover:text-[#2D6A4F] transition-colors">
                                                {{ $product->name }}
                                            </a>
                                            @if(!$product->is_active)
                                                <span class="ml-1 text-xs bg-neutral-200 text-neutral-500 px-1.5 py-0.5 rounded">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="font-bold {{ $stockColor }}">{{ number_format($product->current_stock) }}</span>
                                            <span class="text-neutral-400 text-xs"> {{ $product->unit }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-right font-medium text-neutral-800">
                                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
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
                    <div class="py-12 text-center">
                        <svg class="w-12 h-12 mx-auto text-neutral-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <p class="text-sm font-medium text-neutral-700 mb-1">Belum ada produk</p>
                        <p class="text-sm text-neutral-500 mb-4">Kategori ini belum memiliki produk apapun</p>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('products.create') }}" class="btn-primary text-sm">Tambah Produk</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

    </div>

</x-layouts.app>
