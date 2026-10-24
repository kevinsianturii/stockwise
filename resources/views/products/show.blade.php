<x-layouts.app title="{{ $product->name }}">

    {{-- Page Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center gap-2 text-sm text-neutral-500 mb-2">
                <a href="{{ route('products.index') }}" class="hover:text-[#2D6A4F] transition-colors">Produk</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-neutral-800 font-medium truncate max-w-xs">{{ $product->name }}</span>
            </nav>
            <h1 class="text-2xl font-bold text-neutral-800">{{ $product->name }}</h1>
            <p class="text-sm text-neutral-500 mt-0.5 font-mono">{{ $product->sku }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('stock-movements.create', ['product_id' => $product->id, 'type' => 'in']) }}"
               class="btn-primary inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Catat Masuk
            </a>
            <a href="{{ route('stock-movements.create', ['product_id' => $product->id, 'type' => 'out']) }}"
               class="btn-secondary inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                </svg>
                Catat Keluar
            </a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('products.edit', $product) }}"
                   class="btn-secondary inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
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

    @php
        $isOutOfStock = $product->current_stock == 0;
        $isLowStock = !$isOutOfStock && $product->current_stock <= $product->minimum_stock;
        $stockColor = $isOutOfStock ? 'text-red-600' : ($isLowStock ? 'text-amber-600' : 'text-green-600');
        $stockBg = $isOutOfStock ? 'bg-red-50 border-red-200' : ($isLowStock ? 'bg-amber-50 border-amber-200' : 'bg-green-50 border-green-200');
        $stockPct = $product->minimum_stock > 0
            ? min(100, round(($product->current_stock / max($product->minimum_stock * 2, 1)) * 100))
            : ($product->current_stock > 0 ? 100 : 0);
        $barColor = $isOutOfStock ? 'bg-red-500' : ($isLowStock ? 'bg-amber-500' : 'bg-[#2D6A4F]');
    @endphp

    {{-- Two-column layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        {{-- LEFT: Product Info Card --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Details Card --}}
            <div class="bg-white rounded-xl border border-neutral-200 shadow-sm">
                <div class="px-6 py-4 border-b border-neutral-200 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-neutral-800">Detail Produk</h2>
                    @php
                        $statusLabel = $isOutOfStock ? 'Habis' : ($isLowStock ? 'Stok Menipis' : 'Tersedia');
                        $statusClass = $isOutOfStock ? 'bg-red-100 text-red-700' : ($isLowStock ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700');
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                        <div>
                            <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">SKU</dt>
                            <dd class="font-mono text-sm bg-neutral-100 text-neutral-700 px-2 py-1 rounded inline-block">{{ $product->sku }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Status</dt>
                            <dd>
                                @if($product->is_active)
                                    <span class="inline-flex items-center gap-1 text-sm text-green-700">
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-sm text-neutral-500">
                                        <span class="w-2 h-2 rounded-full bg-neutral-400"></span> Nonaktif
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Kategori</dt>
                            <dd class="text-sm text-neutral-800">
                                @if($product->category)
                                    <a href="{{ route('categories.show', $product->category) }}" class="hover:text-[#2D6A4F] transition-colors">
                                        {{ $product->category->name }}
                                    </a>
                                @else
                                    <span class="text-neutral-400">–</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Supplier</dt>
                            <dd class="text-sm text-neutral-800">{{ $product->supplier->name ?? '–' }}</dd>
                        </div>
                        @if($product->description)
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Deskripsi</dt>
                                <dd class="text-sm text-neutral-700 leading-relaxed">{{ $product->description }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Pricing Card --}}
            <div class="bg-white rounded-xl border border-neutral-200 shadow-sm">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-base font-semibold text-neutral-800">Informasi Harga</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Harga Beli</p>
                            <p class="text-xl font-bold text-neutral-800">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Harga Jual</p>
                            <p class="text-xl font-bold text-[#2D6A4F]">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @if($product->purchase_price > 0 && $product->selling_price > $product->purchase_price)
                        @php
                            $margin = $product->selling_price - $product->purchase_price;
                            $marginPct = round(($margin / $product->purchase_price) * 100, 1);
                        @endphp
                        <div class="mt-4 pt-4 border-t border-neutral-100">
                            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Margin Keuntungan</p>
                            <p class="text-sm font-medium text-green-600">
                                Rp {{ number_format($margin, 0, ',', '.') }}
                                <span class="text-xs text-neutral-500">({{ $marginPct }}%)</span>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- RIGHT: Stock Status Card --}}
        <div class="space-y-4">

            {{-- Stock Info --}}
            <div class="bg-white rounded-xl border border-neutral-200 shadow-sm">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-base font-semibold text-neutral-800">Status Stok</h2>
                </div>
                <div class="p-6 space-y-5">

                    {{-- Large Stock Number --}}
                    <div class="text-center">
                        <p class="text-5xl font-bold {{ $stockColor }}">{{ number_format($product->current_stock) }}</p>
                        <p class="text-sm text-neutral-500 mt-1">{{ $product->unit }} tersedia</p>
                    </div>

                    {{-- Stock Progress Bar --}}
                    <div>
                        <div class="flex justify-between text-xs text-neutral-500 mb-1">
                            <span>0</span>
                            <span>Min: {{ number_format($product->minimum_stock) }}</span>
                        </div>
                        <div class="w-full bg-neutral-200 rounded-full h-3 overflow-hidden">
                            <div class="h-3 rounded-full transition-all duration-500 {{ $barColor }}"
                                 style="width: {{ $stockPct }}%"></div>
                        </div>
                        <p class="text-xs text-neutral-400 mt-1 text-right">{{ $stockPct }}% dari target minimum × 2</p>
                    </div>

                    {{-- Stock details --}}
                    <div class="space-y-2">
                        <div class="flex justify-between items-center py-2 border-b border-neutral-100">
                            <span class="text-sm text-neutral-600">Stok Saat Ini</span>
                            <span class="font-semibold {{ $stockColor }}">{{ number_format($product->current_stock) }} {{ $product->unit }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-neutral-100">
                            <span class="text-sm text-neutral-600">Stok Minimum</span>
                            <span class="font-semibold text-neutral-800">{{ number_format($product->minimum_stock) }} {{ $product->unit }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-neutral-600">Satuan</span>
                            <span class="font-semibold text-neutral-800">{{ $product->unit }}</span>
                        </div>
                    </div>

                    {{-- Warnings --}}
                    @if($isOutOfStock)
                        <div class="flex items-start gap-2 bg-red-50 border border-red-200 rounded-lg p-3">
                            <svg class="w-4 h-4 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <p class="text-xs text-red-800 font-medium">Stok habis! Segera lakukan pengisian stok.</p>
                        </div>
                    @elseif($isLowStock)
                        <div class="flex items-start gap-2 bg-amber-50 border border-amber-200 rounded-lg p-3">
                            <svg class="w-4 h-4 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5C2.962 18.333 3.924 20 5.464 20z"/>
                            </svg>
                            <p class="text-xs text-amber-800 font-medium">Stok menipis! Stok di bawah batas minimum.</p>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl border border-neutral-200 shadow-sm p-4">
                <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-3">Aksi Cepat</p>
                <div class="space-y-2">
                    <a href="{{ route('stock-movements.create', ['product_id' => $product->id, 'type' => 'in']) }}"
                       class="w-full btn-primary text-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Stok (Masuk)
                    </a>
                    <a href="{{ route('stock-movements.create', ['product_id' => $product->id, 'type' => 'out']) }}"
                       class="w-full btn-secondary text-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                        Kurangi Stok (Keluar)
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- Recent Movements Table --}}
    <div class="bg-white rounded-xl border border-neutral-200 shadow-sm">
        <div class="px-6 py-4 border-b border-neutral-200 flex items-center justify-between">
            <h2 class="text-base font-semibold text-neutral-800">Pergerakan Stok Terbaru</h2>
            <a href="{{ route('stock-movements.index', ['product_id' => $product->id]) }}"
               class="text-sm text-[#2D6A4F] hover:underline font-medium">
                Lihat Semua →
            </a>
        </div>

        @if($recentMovements->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-neutral-50 border-b border-neutral-200">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Referensi</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Jenis</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Jumlah</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Stok Sebelum</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Stok Sesudah</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Petugas</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach($recentMovements as $movement)
                            @php
                                $isIn = in_array($movement->type, ['in', 'purchase', 'adjustment_in', 'return']);
                                $typeLabel = match($movement->type) {
                                    'in'             => 'Masuk',
                                    'out'            => 'Keluar',
                                    'purchase'       => 'Pembelian',
                                    'sale'           => 'Penjualan',
                                    'adjustment_in'  => 'Adj. Masuk',
                                    'adjustment_out' => 'Adj. Keluar',
                                    'return'         => 'Retur',
                                    default          => ucfirst($movement->type),
                                };
                                $typeBadge = $isIn
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-red-100 text-red-700';
                            @endphp
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="font-mono text-xs text-neutral-600">{{ $movement->reference ?? '–' }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $typeBadge }}">
                                        {{ $typeLabel }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-semibold {{ $isIn ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $isIn ? '+' : '-' }}{{ number_format(abs($movement->quantity)) }}
                                </td>
                                <td class="px-4 py-3 text-center text-neutral-600">{{ number_format($movement->stock_before) }}</td>
                                <td class="px-4 py-3 text-center font-medium text-neutral-800">{{ number_format($movement->stock_after) }}</td>
                                <td class="px-4 py-3 text-neutral-600">{{ $movement->user->name ?? '–' }}</td>
                                <td class="px-4 py-3 text-neutral-500 text-xs">
                                    {{ $movement->created_at->format('d M Y, H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-10 text-center">
                <svg class="w-10 h-10 mx-auto text-neutral-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/>
                </svg>
                <p class="text-sm text-neutral-500">Belum ada pergerakan stok untuk produk ini</p>
            </div>
        @endif
    </div>

</x-layouts.app>
