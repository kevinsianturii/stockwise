<x-layouts.app title="Laporan Pergerakan Stok">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Laporan Pergerakan Stok</h1>
                <p class="text-sm text-neutral-500 mt-0.5">Analisis ringkasan barang masuk dan keluar per periode.</p>
            </div>
            <a href="{{ route('stock-movements.export', request()->query()) }}" class="btn-secondary !py-2 !px-3.5">
                <i data-lucide="download" class="w-4 h-4"></i>
                Ekspor Mutasi (CSV)
            </a>
        </div>

        {{-- Totals Summary Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="card p-5 border-l-4 border-l-[#2D6A4F]">
                <span class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Total Barang Masuk</span>
                <p class="text-2xl font-bold text-[#2D6A4F] mt-1">
                    +{{ number_format($totals->get('in')?->total_qty ?? 0, 0, ',', '.') }} unit
                </p>
                <span class="text-xs text-neutral-500 mt-0.5 block">
                    {{ number_format($totals->get('in')?->count ?? 0, 0, ',', '.') }} kali transaksi masuk
                </span>
            </div>
            <div class="card p-5 border-l-4 border-l-red-500">
                <span class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Total Barang Keluar</span>
                <p class="text-2xl font-bold text-red-600 mt-1">
                    -{{ number_format($totals->get('out')?->total_qty ?? 0, 0, ',', '.') }} unit
                </p>
                <span class="text-xs text-neutral-500 mt-0.5 block">
                    {{ number_format($totals->get('out')?->count ?? 0, 0, ',', '.') }} kali transaksi keluar
                </span>
            </div>
            <div class="card p-5 border-l-4 border-l-blue-500">
                <span class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Total Penyesuaian</span>
                <p class="text-2xl font-bold text-blue-600 mt-1">
                    {{ number_format($totals->get('adjustment')?->total_qty ?? 0, 0, ',', '.') }} unit
                </p>
                <span class="text-xs text-neutral-500 mt-0.5 block">
                    {{ number_format($totals->get('adjustment')?->count ?? 0, 0, ',', '.') }} kali penyesuaian stok
                </span>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card p-4">
            <form method="GET" action="{{ route('reports.movements') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                <div>
                    <label class="form-label text-xs">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-input text-xs">
                </div>
                <div>
                    <label class="form-label text-xs">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="form-input text-xs">
                </div>
                <div>
                    <label class="form-label text-xs">Jenis Mutasi</label>
                    <select name="type" class="form-input text-xs">
                        <option value="">Semua Jenis</option>
                        <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Barang Masuk</option>
                        <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Barang Keluar</option>
                        <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Penyesuaian</option>
                    </select>
                </div>
                <div>
                    <label class="form-label text-xs">Kategori Produk</label>
                    <select name="category_id" class="form-input text-xs">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn-primary w-full justify-center !text-xs !py-2">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="card overflow-hidden">
            <div class="table-container border-0 rounded-none">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No. Referensi</th>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Jenis</th>
                            <th>Jumlah Mutasi</th>
                            <th>Petugas</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $m)
                            <tr>
                                <td class="font-mono text-xs font-semibold text-neutral-800">
                                    <a href="{{ route('stock-movements.show', $m) }}" class="hover:text-[#2D6A4F] hover:underline">
                                        {{ $m->reference_number }}
                                    </a>
                                </td>
                                <td class="text-xs text-neutral-500">
                                    {{ $m->occurred_at ? $m->occurred_at->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td>
                                    <div class="font-medium text-neutral-900">{{ $m->product->name ?? '-' }}</div>
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
                                <td class="font-semibold text-neutral-900">
                                    {{ $m->type === 'in' ? '+' : ($m->type === 'out' ? '-' : '') }}{{ $m->quantity }} {{ $m->product->unit ?? 'unit' }}
                                </td>
                                <td class="text-xs text-neutral-600">
                                    {{ $m->user->name ?? '-' }}
                                </td>
                                <td class="text-xs text-neutral-500 italic max-w-xs truncate">
                                    {{ $m->notes ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 text-neutral-400 text-sm">
                                    Tidak ada data pergerakan stok pada rentang tanggal ini.
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
