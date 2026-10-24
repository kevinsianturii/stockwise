<x-layouts.app title="Dashboard">
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Ringkasan Inventaris</h1>
                <p class="text-sm text-neutral-500 mt-0.5">Pantau status stok dan pergerakan barang bisnis Anda hari ini.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('stock-movements.create', ['type' => 'in']) }}" class="btn-primary !py-2 !px-3.5">
                    <i data-lucide="arrow-down-left" class="w-4 h-4"></i>
                    Barang Masuk
                </a>
                <a href="{{ route('stock-movements.create', ['type' => 'out']) }}" class="btn-secondary !py-2 !px-3.5">
                    <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                    Barang Keluar
                </a>
            </div>
        </div>

        {{-- Metric Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Produk --}}
            <div class="card p-5 flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Total Produk</span>
                    <p class="text-2xl font-bold text-neutral-900 mt-1">{{ number_format($totalProducts, 0, ',', '.') }}</p>
                    <span class="text-xs text-neutral-500 mt-1 block">Produk aktif terdaftar</span>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-50 text-[#2D6A4F] flex items-center justify-center shrink-0">
                    <i data-lucide="package" class="w-6 h-6"></i>
                </div>
            </div>

            {{-- Total Unit Stok --}}
            <div class="card p-5 flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Total Unit Stok</span>
                    <p class="text-2xl font-bold text-neutral-900 mt-1">{{ number_format($totalUnits, 0, ',', '.') }}</p>
                    <span class="text-xs text-neutral-500 mt-1 block">Akumulasi kuantitas stok</span>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <i data-lucide="layers" class="w-6 h-6"></i>
                </div>
            </div>

            {{-- Stok Menipis & Habis --}}
            <div class="card p-5 flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Perlu Restok</span>
                    <div class="flex items-baseline gap-2 mt-1">
                        <p class="text-2xl font-bold text-amber-600">{{ number_format($lowStockCount, 0, ',', '.') }}</p>
                        @if($outOfStockCount > 0)
                            <span class="text-xs font-semibold text-red-600">({{ $outOfStockCount }} habis)</span>
                        @endif
                    </div>
                    <a href="{{ route('reports.low-stock') }}" class="text-xs text-[#2D6A4F] hover:underline font-medium mt-1 inline-block">
                        Lihat daftar restok &rarr;
                    </a>
                </div>
                <div class="w-12 h-12 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                </div>
            </div>

            {{-- Transaksi Bulan Ini --}}
            <div class="card p-5 flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Mutasi Bulan Ini</span>
                    <p class="text-2xl font-bold text-neutral-900 mt-1">{{ number_format($movementsThisMonth, 0, ',', '.') }}</p>
                    <a href="{{ route('stock-movements.index') }}" class="text-xs text-[#2D6A4F] hover:underline font-medium mt-1 inline-block">
                        Semua transaksi &rarr;
                    </a>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                    <i data-lucide="arrow-left-right" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        {{-- Visual Chart & Need Restock Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Chart Column --}}
            <div class="card p-5 lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-semibold text-neutral-800">Tren Pergerakan Stok</h2>
                        <p class="text-xs text-neutral-500">Volume barang masuk dan barang keluar 30 hari terakhir</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#2D6A4F]"></span> Masuk</span>
                        <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Keluar</span>
                    </div>
                </div>
                <div class="relative h-72 w-full">
                    <canvas id="movementChart"></canvas>
                </div>
            </div>

            {{-- Low Stock Alert Column --}}
            <div class="card p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-semibold text-neutral-800">Peringatan Stok Menipis</h2>
                        <a href="{{ route('reports.low-stock') }}" class="text-xs text-[#2D6A4F] hover:underline font-medium">Semua</a>
                    </div>

                    @if($lowStockProducts->isEmpty())
                        <div class="text-center py-10">
                            <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center mx-auto mb-2">
                                <i data-lucide="check" class="w-5 h-5"></i>
                            </div>
                            <p class="text-sm font-medium text-neutral-800">Semua Stok Aman</p>
                            <p class="text-xs text-neutral-500 mt-0.5">Tidak ada produk di bawah stok minimum.</p>
                        </div>
                    @else
                        <div class="divide-y divide-neutral-100">
                            @foreach($lowStockProducts as $prod)
                                <div class="py-2.5 flex items-center justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-neutral-800 truncate">{{ $prod->name }}</p>
                                        <p class="text-xs text-neutral-500">Min: {{ $prod->minimum_stock }} {{ $prod->unit }}</p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span class="inline-block px-2 py-0.5 text-xs font-bold rounded {{ $prod->current_stock == 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $prod->current_stock }} {{ $prod->unit }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="pt-4 border-t border-neutral-100">
                    <a href="{{ route('stock-movements.create', ['type' => 'in']) }}" class="btn-secondary w-full justify-center !text-xs">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        Restok Barang Sekarang
                    </a>
                </div>
            </div>
        </div>

        {{-- Recent Movements Table --}}
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-neutral-800">Riwayat Transaksi Terakhir</h2>
                    <p class="text-xs text-neutral-500">10 pergerakan inventaris paling baru</p>
                </div>
                <a href="{{ route('stock-movements.index') }}" class="text-xs font-medium text-[#2D6A4F] hover:underline">
                    Lihat Semua Riwayat &rarr;
                </a>
            </div>

            <div class="table-container border-0 rounded-none">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No. Referensi</th>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Petugas</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentMovements as $mov)
                            <tr>
                                <td class="font-mono text-xs font-semibold text-neutral-800">
                                    {{ $mov->reference_number }}
                                </td>
                                <td class="text-xs text-neutral-500">
                                    {{ $mov->occurred_at ? $mov->occurred_at->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td>
                                    <div class="font-medium text-neutral-800">{{ $mov->product->name ?? '-' }}</div>
                                    <div class="text-[11px] text-neutral-400 font-mono">{{ $mov->product->sku ?? '' }}</div>
                                </td>
                                <td>
                                    @if($mov->type === 'in')
                                        <span class="badge-green">Masuk</span>
                                    @elseif($mov->type === 'out')
                                        <span class="badge-red">Keluar</span>
                                    @else
                                        <span class="badge-blue">Penyesuaian</span>
                                    @endif
                                </td>
                                <td class="font-semibold text-neutral-800">
                                    {{ $mov->type === 'in' ? '+' : ($mov->type === 'out' ? '-' : '') }}{{ $mov->quantity }} {{ $mov->product->unit ?? 'unit' }}
                                </td>
                                <td class="text-xs text-neutral-600">
                                    {{ $mov->user->name ?? '-' }}
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('stock-movements.show', $mov) }}" class="text-xs font-medium text-[#2D6A4F] hover:underline">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-neutral-400 text-sm">
                                    Belum ada transaksi stok yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = @json($chartData);
            const ctx = document.getElementById('movementChart');

            if (ctx && typeof Chart !== 'undefined') {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label: 'Barang Masuk',
                                data: chartData.inData,
                                backgroundColor: '#2D6A4F',
                                borderRadius: 4,
                            },
                            {
                                label: 'Barang Keluar',
                                data: chartData.outData,
                                backgroundColor: '#EF4444',
                                borderRadius: 4,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                padding: 10,
                                cornerRadius: 6
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: { size: 11 }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#F3F4F6'
                                },
                                ticks: {
                                    font: { size: 11 },
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-layouts.app>
