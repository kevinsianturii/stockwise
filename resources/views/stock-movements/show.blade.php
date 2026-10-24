<x-layouts.app title="Detail Transaksi Stok">
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Detail Transaksi Stok</h1>
                <p class="text-sm font-mono text-neutral-500 mt-0.5">{{ $stockMovement->reference_number }}</p>
            </div>
            <a href="{{ route('stock-movements.index') }}" class="btn-secondary !text-xs !py-1.5">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali ke Daftar
            </a>
        </div>

        <div class="card p-6 divide-y divide-neutral-100">
            {{-- Summary Header --}}
            <div class="pb-5 flex items-center justify-between">
                <div>
                    <span class="text-xs text-neutral-400 font-medium uppercase tracking-wider">Jenis Mutasi</span>
                    <div class="mt-1">
                        @if($stockMovement->type === 'in')
                            <span class="badge-green text-sm px-3 py-1 font-semibold">Barang Masuk</span>
                        @elseif($stockMovement->type === 'out')
                            <span class="badge-red text-sm px-3 py-1 font-semibold">Barang Keluar</span>
                        @else
                            <span class="badge-blue text-sm px-3 py-1 font-semibold">Penyesuaian Stok</span>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs text-neutral-400 font-medium uppercase tracking-wider">Kuantitas</span>
                    <p class="text-2xl font-bold {{ $stockMovement->type === 'in' ? 'text-[#2D6A4F]' : ($stockMovement->type === 'out' ? 'text-red-600' : 'text-blue-600') }} mt-0.5">
                        {{ $stockMovement->type === 'in' ? '+' : ($stockMovement->type === 'out' ? '-' : '') }}{{ $stockMovement->quantity }} {{ $stockMovement->product->unit ?? 'unit' }}
                    </p>
                </div>
            </div>

            {{-- Audit Trail: Before and After --}}
            <div class="py-5">
                <span class="text-xs text-neutral-400 font-medium uppercase tracking-wider">Audit Perubahan Stok</span>
                <div class="grid grid-cols-3 gap-3 mt-3 text-center">
                    <div class="p-3 rounded-lg bg-neutral-50 border border-neutral-100">
                        <span class="text-xs text-neutral-500 block">Stok Sebelum</span>
                        <span class="text-lg font-bold text-neutral-800">{{ $stockMovement->stock_before }}</span>
                    </div>
                    <div class="flex items-center justify-center text-neutral-400">
                        <i data-lucide="arrow-right" class="w-6 h-6"></i>
                    </div>
                    <div class="p-3 rounded-lg bg-neutral-50 border border-neutral-100">
                        <span class="text-xs text-neutral-500 block">Stok Sesudah</span>
                        <span class="text-lg font-bold text-neutral-800">{{ $stockMovement->stock_after }}</span>
                    </div>
                </div>
            </div>

            {{-- Product Details --}}
            <div class="py-5 space-y-3">
                <span class="text-xs text-neutral-400 font-medium uppercase tracking-wider">Informasi Produk</span>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-neutral-500">Nama Produk:</span>
                    <a href="{{ route('products.show', $stockMovement->product_id) }}" class="font-semibold text-[#2D6A4F] hover:underline">
                        {{ $stockMovement->product->name ?? '-' }}
                    </a>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-neutral-500">SKU Produk:</span>
                    <span class="font-mono text-neutral-800">{{ $stockMovement->product->sku ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-neutral-500">Kategori:</span>
                    <span class="text-neutral-800">{{ $stockMovement->product->category->name ?? '-' }}</span>
                </div>
                @if($stockMovement->unit_cost)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-neutral-500">Harga Satuan Masuk:</span>
                        <span class="font-semibold text-neutral-800">Rp {{ number_format($stockMovement->unit_cost, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>

            {{-- Metadata & Notes --}}
            <div class="pt-5 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-neutral-500">Waktu Transaksi:</span>
                    <span class="text-neutral-800">{{ $stockMovement->occurred_at ? $stockMovement->occurred_at->format('d F Y, H:i') : '-' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-neutral-500">Dicatat Oleh:</span>
                    <span class="font-medium text-neutral-800">{{ $stockMovement->user->name ?? '-' }} ({{ $stockMovement->user->email ?? '' }})</span>
                </div>
                <div class="pt-2">
                    <span class="text-xs text-neutral-400 font-medium uppercase tracking-wider block mb-1">Catatan Transaksi:</span>
                    <p class="text-sm text-neutral-700 bg-neutral-50 rounded-lg p-3 border border-neutral-100 italic">
                        {{ $stockMovement->notes ?: 'Tidak ada catatan.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
