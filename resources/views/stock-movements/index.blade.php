<x-layouts.app title="Transaksi Stok">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Riwayat Mutasi & Transaksi Stok</h1>
                <p class="text-sm text-neutral-500 mt-0.5">Catatan lengkap pergerakan barang masuk, keluar, dan penyesuaian.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('stock-movements.export', request()->query()) }}" class="btn-secondary !py-2 !px-3">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Ekspor CSV
                </a>
                <a href="{{ route('stock-movements.create', ['type' => 'in']) }}" class="btn-primary !py-2 !px-3.5">
                    <i data-lucide="arrow-down-left" class="w-4 h-4"></i>
                    Barang Masuk
                </a>
                <a href="{{ route('stock-movements.create', ['type' => 'out']) }}" class="btn-secondary !py-2 !px-3.5 text-red-600 border-red-200 hover:bg-red-50">
                    <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                    Barang Keluar
                </a>
            </div>
        </div>

        {{-- Filter Box --}}
        <div class="card p-4">
            <form method="GET" action="{{ route('stock-movements.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                <div>
                    <label class="form-label text-xs">Cari Referensi / Produk</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="TRX-... atau nama produk" class="form-input text-xs">
                </div>
                <div>
                    <label class="form-label text-xs">Jenis Transaksi</label>
                    <select name="type" class="form-input text-xs">
                        <option value="">Semua Jenis</option>
                        <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Barang Masuk</option>
                        <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Barang Keluar</option>
                        <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Penyesuaian</option>
                    </select>
                </div>
                <div>
                    <label class="form-label text-xs">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input text-xs">
                </div>
                <div>
                    <label class="form-label text-xs">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input text-xs">
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="btn-primary w-full justify-center !text-xs !py-2">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'type', 'date_from', 'date_to', 'user_id']))
                        <a href="{{ route('stock-movements.index') }}" class="btn-secondary !p-2 text-neutral-500" title="Reset filter">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table Card --}}
        <div class="card overflow-hidden">
            <div class="table-container border-0 rounded-none">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No. Referensi</th>
                            <th>Tanggal & Waktu</th>
                            <th>Produk</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Stok (Sebelum &rarr; Sesudah)</th>
                            <th>Petugas</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $m)
                            <tr>
                                <td class="font-mono text-xs font-semibold text-neutral-800">
                                    {{ $m->reference_number }}
                                </td>
                                <td class="text-xs text-neutral-500">
                                    {{ $m->occurred_at ? $m->occurred_at->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td>
                                    <div class="font-medium text-neutral-800">{{ $m->product->name ?? '-' }}</div>
                                    <div class="text-[11px] text-neutral-400 font-mono">{{ $m->product->sku ?? '' }}</div>
                                </td>
                                <td>
                                    @if($m->type === 'in')
                                        <span class="badge-green">Masuk</span>
                                    @elseif($m->type === 'out')
                                        <span class="badge-red">Keluar</span>
                                    @else
                                        <span class="badge-blue">Penyesuaian</span>
                                    @endif
                                </td>
                                <td class="font-semibold text-neutral-800">
                                    {{ $m->type === 'in' ? '+' : ($m->type === 'out' ? '-' : '') }}{{ $m->quantity }} {{ $m->product->unit ?? 'unit' }}
                                </td>
                                <td class="text-xs text-neutral-600 font-mono">
                                    {{ $m->stock_before }} &rarr; <span class="font-semibold text-neutral-800">{{ $m->stock_after }}</span>
                                </td>
                                <td class="text-xs text-neutral-600">
                                    {{ $m->user->name ?? '-' }}
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('stock-movements.show', $m) }}" class="text-xs font-medium text-[#2D6A4F] hover:underline">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-10 text-neutral-400 text-sm">
                                    Tidak ada data transaksi stok yang sesuai dengan kriteria filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($movements->hasPages())
                <div class="p-4 border-t border-neutral-200">
                    {{ $movements->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
