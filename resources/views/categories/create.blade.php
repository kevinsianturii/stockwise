<x-layouts.app title="Tambah Kategori">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-neutral-500 mb-4">
        <a href="{{ route('categories.index') }}" class="hover:text-[#2D6A4F] transition-colors">Kategori</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-neutral-800 font-medium">Tambah</span>
    </nav>

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-800">Tambah Kategori Baru</h1>
        <p class="text-sm text-neutral-600 mt-1">Buat kategori baru untuk mengelompokkan produk</p>
    </div>

    {{-- Form --}}
    <div class="max-w-2xl">
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-6">
            @csrf

            <div class="bg-white rounded-xl border border-neutral-200 shadow-sm">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-base font-semibold text-neutral-800">Informasi Kategori</h2>
                </div>
                <div class="p-6 space-y-5">

                    {{-- Nama --}}
                    <div>
                        <label for="name" class="form-label">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Contoh: Elektronik, Makanan, Peralatan"
                            class="form-input @error('name') border-red-400 @enderror"
                            required
                            autofocus
                        />
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Deskripsi singkat tentang kategori ini (opsional)"
                            class="form-input @error('description') border-red-400 @enderror"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-3 justify-end">
                <a href="{{ route('categories.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Kategori
                </button>
            </div>

        </form>
    </div>

</x-layouts.app>
