<x-layouts.app title="Manajemen Pengguna">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Manajemen Akun Pengguna</h1>
                <p class="text-sm text-neutral-500 mt-0.5">Kelola staf, admin, dan hak akses operasional StockWise.</p>
            </div>
            <a href="{{ route('users.create') }}" class="btn-primary !py-2 !px-3.5">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                Tambah Pengguna
            </a>
        </div>

        {{-- Filter Search --}}
        <div class="card p-4">
            <form method="GET" action="{{ route('users.index') }}" class="flex gap-3">
                <div class="flex-1 max-w-sm">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email pengguna..." class="form-input text-xs">
                </div>
                <button type="submit" class="btn-primary !text-xs !py-2">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    Cari
                </button>
            </form>
        </div>

        {{-- Users Table --}}
        <div class="card overflow-hidden">
            <div class="table-container border-0 rounded-none">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Email</th>
                            <th>Peran (Role)</th>
                            <th>Status Akun</th>
                            <th>Tanggal Terdaftar</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#2D6A4F]/20 text-[#2D6A4F] font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-neutral-900">{{ $user->name }}</p>
                                            @if($user->id === auth()->id())
                                                <span class="text-[10px] text-[#2D6A4F] font-semibold">(Akun Anda)</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-xs text-neutral-600">{{ $user->email }}</td>
                                <td>
                                    @if($user->isAdmin())
                                        <span class="badge-blue font-semibold">Administrator</span>
                                    @else
                                        <span class="badge-neutral font-semibold">Staf Gudang</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge-green">Aktif</span>
                                    @else
                                        <span class="badge-red">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-xs text-neutral-500">
                                    {{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('users.edit', $user) }}" class="text-xs font-medium text-neutral-600 hover:text-neutral-900">
                                            Edit
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-neutral-400 text-sm">
                                    Tidak ada data pengguna.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="p-4 border-t border-neutral-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
