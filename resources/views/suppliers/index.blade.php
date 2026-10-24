<x-layouts.app title="Daftar Supplier">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Daftar Supplier</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola semua supplier produk Anda</p>
            </div>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('suppliers.create') }}" class="btn-primary inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Supplier
                </a>
            @endif
        </div>

        {{-- Search Form --}}
        <div class="card">
            <form method="GET" action="{{ route('suppliers.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama, kontak, atau email supplier..."
                        class="form-input w-full"
                    />
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                        </svg>
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('suppliers.index') }}" class="btn-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Suppliers Table --}}
        <div class="card p-0">
            @if($suppliers->count() > 0)
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kontak Person</th>
                                <th>Telepon</th>
                                <th>Email</th>
                                <th>Jumlah Produk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                                <tr>
                                    <td class="font-medium text-gray-900">{{ $supplier->name }}</td>
                                    <td class="text-gray-600">{{ $supplier->contact_person ?? '-' }}</td>
                                    <td class="text-gray-600">{{ $supplier->phone ?? '-' }}</td>
                                    <td class="text-gray-600">{{ $supplier->email ?? '-' }}</td>
                                    <td>
                                        <span class="badge-blue">{{ $supplier->products_count }} produk</span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('suppliers.show', $supplier) }}"
                                               class="text-[#2D6A4F] hover:text-[#1B4332] font-medium text-sm">
                                                Lihat
                                            </a>
                                            @if(auth()->user()->isAdmin())
                                                <span class="text-gray-300">|</span>
                                                <a href="{{ route('suppliers.edit', $supplier) }}"
                                                   class="text-amber-600 hover:text-amber-700 font-medium text-sm">
                                                    Edit
                                                </a>
                                                <span class="text-gray-300">|</span>
                                                <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}"
                                                      onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-700 font-medium text-sm">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($suppliers->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $suppliers->withQueryString()->links() }}
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Belum Ada Supplier</h3>
                    <p class="text-gray-500 mb-4">
                        @if(request('search'))
                            Tidak ada supplier yang cocok dengan pencarian "{{ request('search') }}"
                        @else
                            Mulai dengan menambahkan supplier pertama Anda
                        @endif
                    </p>
                    @if(auth()->user()->isAdmin() && !request('search'))
                        <a href="{{ route('suppliers.create') }}" class="btn-primary">Tambah Supplier</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
