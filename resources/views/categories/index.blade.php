<x-layouts.app title="Daftar Kategori">

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-neutral-800">Daftar Kategori</h1>
            <p class="text-sm text-neutral-600 mt-1">Kelola kategori produk dalam inventaris Anda</p>
        </div>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('categories.create') }}" class="btn-primary inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kategori
            </a>
        @endif
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
    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5C2.962 18.333 3.924 20 5.464 20z"/>
            </svg>
            <p class="text-sm text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Search Form --}}
    <div class="bg-white rounded-xl border border-neutral-200 p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('categories.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="form-label">Cari Kategori</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama kategori..."
                    class="form-input"
                />
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                    Cari
                </button>
                @if(request()->filled('search'))
                    <a href="{{ route('categories.index') }}" class="btn-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Results Count --}}
    <div class="mb-3 text-sm text-neutral-600">
        Menampilkan <span class="font-semibold text-neutral-800">{{ $categories->firstItem() ?? 0 }}</span>–<span class="font-semibold text-neutral-800">{{ $categories->lastItem() ?? 0 }}</span>
        dari <span class="font-semibold text-neutral-800">{{ $categories->total() }}</span> kategori
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-neutral-200 shadow-sm overflow-hidden">
        @if($categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-neutral-50 border-b border-neutral-200">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Slug</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Jumlah Produk</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach($categories as $category)
                            <tr class="hover:bg-neutral-50 transition-colors">

                                {{-- Nama --}}
                                <td class="px-4 py-3">
                                    <a href="{{ route('categories.show', $category) }}" class="font-medium text-neutral-800 hover:text-[#2D6A4F] transition-colors">
                                        {{ $category->name }}
                                    </a>
                                </td>

                                {{-- Slug --}}
                                <td class="px-4 py-3">
                                    <span class="font-mono text-xs bg-neutral-100 text-neutral-600 px-2 py-1 rounded">{{ $category->slug }}</span>
                                </td>

                                {{-- Deskripsi --}}
                                <td class="px-4 py-3 text-neutral-600 max-w-xs">
                                    <p class="truncate">{{ $category->description ?: '–' }}</p>
                                </td>

                                {{-- Jumlah Produk --}}
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold
                                        {{ $category->products_count > 0 ? 'bg-[#2D6A4F] text-white' : 'bg-neutral-100 text-neutral-500' }}">
                                        {{ $category->products_count }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1">
                                        {{-- View --}}
                                        <a href="{{ route('categories.show', $category) }}"
                                           class="p-1.5 text-neutral-500 hover:text-[#2D6A4F] hover:bg-green-50 rounded-lg transition-colors"
                                           title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        @if(auth()->user()->isAdmin())
                                            {{-- Edit --}}
                                            <a href="{{ route('categories.edit', $category) }}"
                                               class="p-1.5 text-neutral-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                               title="Edit Kategori">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>

                                            {{-- Delete --}}
                                            <div x-data>
                                                @if($category->products_count > 0)
                                                    <button type="button"
                                                            @click="alert('Kategori \'{{ addslashes($category->name) }}\' tidak dapat dihapus karena memiliki {{ $category->products_count }} produk. Pindahkan atau hapus produk terlebih dahulu.')"
                                                            class="p-1.5 text-neutral-300 cursor-not-allowed rounded-lg"
                                                            title="Tidak dapat dihapus (ada produk)">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                @else
                                                    <button type="button"
                                                            @click="if(confirm('Hapus kategori \'{{ addslashes($category->name) }}\'? Tindakan ini tidak dapat dibatalkan.')) $refs.deleteCatForm{{ $category->id }}.submit()"
                                                            class="p-1.5 text-neutral-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                            title="Hapus Kategori">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                    <form x-ref="deleteCatForm{{ $category->id }}"
                                                          method="POST"
                                                          action="{{ route('categories.destroy', $category) }}"
                                                          class="hidden">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($categories->hasPages())
                <div class="px-4 py-3 border-t border-neutral-200">
                    {{ $categories->withQueryString()->links() }}
                </div>
            @endif

        @else
            {{-- Empty State --}}
            <div class="py-16 text-center">
                <svg class="w-16 h-16 mx-auto text-neutral-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                </svg>
                <h3 class="text-lg font-semibold text-neutral-700 mb-1">Tidak ada kategori ditemukan</h3>
                <p class="text-sm text-neutral-500 mb-4">
                    @if(request()->filled('search'))
                        Tidak ada kategori yang cocok dengan pencarian Anda.
                        <a href="{{ route('categories.index') }}" class="text-[#2D6A4F] hover:underline">Reset pencarian</a>
                    @else
                        Belum ada kategori yang ditambahkan.
                    @endif
                </p>
                @if(auth()->user()->isAdmin() && !request()->filled('search'))
                    <a href="{{ route('categories.create') }}" class="btn-primary">Tambah Kategori Pertama</a>
                @endif
            </div>
        @endif
    </div>

</x-layouts.app>
