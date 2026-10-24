<x-layouts.app title="Laporan Stok Menipis & Habis">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Peringatan Restok Barang</h1>
                <p class="text-sm text-neutral-500 mt-0.5">Daftar produk yang stoknya habis atau sudah mencapai batas minimum pemesanan.</p>
            </div>
            <a href="{{ route('stock-movements.create', ['type' => 'in']) }}" class="btn-primary !py-2 !px-3.5">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                Catat Barang Masuk (Restok)
            </a>
        </div>

        @if($outOfStock->isEmpty() && $lowStock->isEmpty())
            <div class="card p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-green-50 text-[#2D6A4F] flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="check-circle-2" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-neutral-800">Semua Stok Produk Aman</h3>
                <p class="text-sm text-neutral-500 mt-1 max-w-md mx-auto">
                    Tidak ada produk yang habis atau berada di bawah batas minimum stok saat ini. Kinerja inventaris toko Anda terpantau prima!
                </p>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" class="btn-secondary !text-xs">
                        Lihat Katalog Produk
                    </a>
                </div>
            </div>
        @else
            {{-- Out of Stock Section --}}
            @if($outOfStock->isNotEmpty())
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-600"></span>
                        <h2 class="text-base font-bold text-red-700">Stok Habis (Kosong) — Mendesak ({{ $outOfStock->count() }})</h2>
                    </div>
                    <div class="card overflow-hidden border-red-200">
                        <div class="table-container border-0 rounded-none">
                            <table class="table">
                                <thead class="bg-red-50/50">
                                    <tr>
                                        <th>SKU</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Stok Saat Ini</th>
                                        <th>Stok Minimum</th>
                                        <th>Harga Beli</th>
                                        <th class="text-right">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-red-100">
                                    @foreach($outOfStock as $item)
                                        <tr class="hover:bg-red-50/30">
                                            <td class="font-mono text-xs font-semibold text-neutral-800">{{ $item->sku }}</td>
                                            <td class="font-medium text-neutral-900">{{ $item->name }}</td>
                                            <td class="text-xs text-neutral-600">{{ $item->category->name ?? '-' }}</td>
                                            <td>
                                                <span class="badge-red font-bold">0 {{ $item->unit }}</span>
                                            </td>
                                            <td class="text-xs text-neutral-500">{{ $item->minimum_stock }} {{ $item->unit }}</td>
                                            <td class="text-xs text-neutral-700">Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</td>
                                            <td class="text-right">
                                                <a href="{{ route('stock-movements.create', ['type' => 'in', 'product_id' => $item->id]) }}" class="btn-primary !text-xs !py-1 !px-2.5">
                                                    Restok Sekarang
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Low Stock Section --}}
            @if($lowStock->isNotEmpty())
                <div class="space-y-3 pt-2">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                        <h2 class="text-base font-bold text-amber-800">Stok Menipis — Segera Pesan ({{ $lowStock->count() }})</h2>
                    </div>
                    <div class="card overflow-hidden border-amber-200">
                        <div class="table-container border-0 rounded-none">
                            <table class="table">
                                <thead class="bg-amber-50/50">
                                    <tr>
                                        <th>SKU</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Stok Tersedia</th>
                                        <th>Ambang Minimum</th>
                                        <th>Estimasi Biaya Restok</th>
                                        <th class="text-right">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-amber-100">
                                    @foreach($lowStock as $item)
                                        <tr class="hover:bg-amber-50/30">
                                            <td class="font-mono text-xs font-semibold text-neutral-800">{{ $item->sku }}</td>
                                            <td class="font-medium text-neutral-900">{{ $item->name }}</td>
                                            <td class="text-xs text-neutral-600">{{ $item->category->name ?? '-' }}</td>
                                            <td>
                                                <span class="badge-amber font-bold">{{ $item->current_stock }} {{ $item->unit }}</span>
                                            </td>
                                            <td class="text-xs text-neutral-500">{{ $item->minimum_stock }} {{ $item->unit }}</td>
                                            <td class="text-xs text-neutral-700 font-mono">
                                                @php
                                                    $defisit = max(0, $item->minimum_stock - $item->current_stock + 5);
                                                @endphp
                                                Rp {{ number_format($defisit * $item->purchase_price, 0, ',', '.') }}
                                                <span class="text-[10px] text-neutral-400 block">({{ $defisit }} unit)</span>
                                            </td>
                                            <td class="text-right">
                                                <a href="{{ route('stock-movements.create', ['type' => 'in', 'product_id' => $item->id]) }}" class="btn-secondary !text-xs !py-1 !px-2.5">
                                                    Restok
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</x-layouts.app>
