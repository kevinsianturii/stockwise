<x-layouts.app title="Detail Supplier">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('suppliers.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $supplier->name }}</h1>
                    <p class="text-sm text-gray-500 mt-1">Detail informasi supplier</p>
                </div>
            </div>
            @if(auth()->user()->isAdmin())
                <div class="flex gap-2">
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn-secondary">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}"
                          onsubmit="return confirm('Yakin ingin menghapus supplier ini? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Supplier Info Card --}}
        <div class="card">
            <h2 class="text-lg font-semibold text-gray-900 mb-5">Informasi Supplier</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nama</p>
                        <p class="text-gray-900 font-medium mt-1">{{ $supplier->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Kontak Person</p>
                        <p class="text-gray-900 mt-1">{{ $supplier->contact_person ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Telepon</p>
                        <p class="text-gray-900 mt-1">
                            @if($supplier->phone)
                                <a href="tel:{{ $supplier->phone }}" class="text-[#2D6A4F] hover:underline">
                                    {{ $supplier->phone }}
                                </a>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Email</p>
                        <p class="text-gray-900 mt-1">
                            @if($supplier->email)
                                <a href="mailto:{{ $supplier->email }}" class="text-[#2D6A4F] hover:underline">
                                    {{ $supplier->email }}
                                </a>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Alamat</p>
                        <p class="text-gray-900 mt-1">{{ $supplier->address ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Catatan</p>
                        <p class="text-gray-900 mt-1">{{ $supplier->notes ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Terdaftar Sejak</p>
                        <p class="text-gray-900 mt-1">{{ $supplier->created_at->format('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Products Table --}}
        <div class="card p-0">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Produk dari Supplier Ini</h2>
                <span class="badge-blue">{{ $products->total() }} produk</span>
            </div>

            @if($products->count() > 0)
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td class="text-xs font-mono text-gray-500">{{ $product->sku }}</td>
                                    <td class="font-medium text-gray-900">
                                        <a href="{{ route('products.show', $product) }}"
                                           class="text-[#2D6A4F] hover:underline">
                                            {{ $product->name }}
                                        </a>
                                    </td>
                                    <td class="text-gray-600">{{ $product->category->name ?? '-' }}</td>
                                    <td>
                                        @if($product->current_stock <= 0)
                                            <span class="badge-red">{{ $product->current_stock }} {{ $product->unit }}</span>
                                        @elseif($product->current_stock <= $product->min_stock)
                                            <span class="badge-amber">{{ $product->current_stock }} {{ $product->unit }}</span>
                                        @else
                                            <span class="badge-green">{{ $product->current_stock }} {{ $product->unit }}</span>
                                        @endif
                                    </td>
                                    <td class="text-gray-600">
                                        Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                                    </td>
                                    <td class="text-gray-600">
                                        Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge-green">Aktif</span>
                                        @else
                                            <span class="badge-neutral">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($products->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="text-gray-500">Belum ada produk dari supplier ini</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
