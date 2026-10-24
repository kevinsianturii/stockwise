<x-layouts.app title="Edit Kategori">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-neutral-500 mb-4">
        <a href="{{ route('categories.index') }}" class="hover:text-[#2D6A4F] transition-colors">Kategori</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('categories.show', $category) }}" class="hover:text-[#2D6A4F] transition-colors">{{ $category->name }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-neutral-800 font-medium">Edit</span>
    </nav>

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-800">Edit Kategori</h1>
        <p class="text-sm text-neutral-600 mt-1">Perbarui informasi kategori <span class="font-medium text-neutral-800">{{ $category->name }}</span></p>
    </div>

    {{-- Form --}}
    <div class="max-w-2xl">
        <form method="POST" action="{{ route('categories.update', $category) }}" class="space-y-6">
            @csrf
            @method('PUT')

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
                            value="{{ old('name', $category->name) }}"
                            placeholder="Contoh: Elektronik, Makanan, Peralatan"
                            class="form-input @error('name') border-red-400 @enderror"
                            required
                            autofocus
                        />
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Slug (read-only info) --}}
                    <div>
                        <label class="form-label">Slug (dibuat otomatis)</label>
                        <div class="flex items-center gap-2">
                            <input
                                type="text"
                                value="{{ $category->slug }}"
                                class="form-input bg-neutral-50 text-neutral-500 font-mono text-sm cursor-not-allowed"
                                readonly
                                disabled
                            />
                        </div>
                        <p class="mt-1 text-xs text-neutral-500">Slug akan diperbarui otomatis mengikuti nama kategori</p>
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
                        >{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Meta info --}}
            <div class="bg-neutral-50 rounded-xl border border-neutral-200 p-4 text-sm text-neutral-500 flex flex-wrap gap-6">
                <div>
                    <span class="font-medium text-neutral-700">Dibuat:</span>
                    {{ $category->created_at->format('d M Y, H:i') }}
                </div>
                <div>
                    <span class="font-medium text-neutral-700">Diperbarui:</span>
                    {{ $category->updated_at->format('d M Y, H:i') }}
                </div>
                <div>
                    <span class="font-medium text-neutral-700">Jumlah Produk:</span>
                    {{ $category->products()->count() }} produk
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-3 justify-end">
                <a href="{{ route('categories.show', $category) }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</x-layouts.app>
