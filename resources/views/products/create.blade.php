<x-layouts.app title="Tambah Produk Baru">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-neutral-500 mb-4">
        <a href="{{ route('products.index') }}" class="hover:text-[#2D6A4F] transition-colors">Produk</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-neutral-800 font-medium">Tambah</span>
    </nav>

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-800">Tambah Produk Baru</h1>
        <p class="text-sm text-neutral-600 mt-1">Lengkapi informasi di bawah untuk menambahkan produk baru ke inventaris</p>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('products.store') }}" class="space-y-6">
        @csrf

        {{-- Main Info Card --}}
        <div class="bg-white rounded-xl border border-neutral-200 shadow-sm">
            <div class="px-6 py-4 border-b border-neutral-200">
                <h2 class="text-base font-semibold text-neutral-800">Informasi Produk</h2>
            </div>
            <div class="p-6 space-y-5">

                {{-- SKU --}}
                <div>
                    <label for="sku" class="form-label">
                        SKU <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="sku"
                        name="sku"
                        value="{{ old('sku') }}"
                        placeholder="Contoh: PRD-001"
                        class="form-input @error('sku') border-red-400 @enderror"
                        required
                    />
                    <p class="mt-1 text-xs text-neutral-500">SKU digunakan sebagai pengenal unik produk dalam sistem</p>
                    @error('sku')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Produk --}}
                <div>
                    <label for="name" class="form-label">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama produk"
                        class="form-input @error('name') border-red-400 @enderror"
                        required
                    />
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="category_id" class="form-label">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="category_id"
                        name="category_id"
                        class="form-input @error('category_id') border-red-400 @enderror"
                        required
                    >
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Supplier --}}
                <div>
                    <label for="supplier_id" class="form-label">Supplier</label>
                    <select
                        id="supplier_id"
                        name="supplier_id"
                        class="form-input @error('supplier_id') border-red-400 @enderror"
                    >
                        <option value="">Tanpa Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        placeholder="Deskripsi singkat produk (opsional)"
                        class="form-input @error('description') border-red-400 @enderror"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Pricing Card --}}
        <div class="bg-white rounded-xl border border-neutral-200 shadow-sm">
            <div class="px-6 py-4 border-b border-neutral-200">
                <h2 class="text-base font-semibold text-neutral-800">Informasi Harga</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Harga Beli --}}
                    <div>
                        <label for="purchase_price" class="form-label">Harga Beli</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500 font-medium text-sm">Rp</span>
                            <input
                                type="number"
                                id="purchase_price"
                                name="purchase_price"
                                value="{{ old('purchase_price', 0) }}"
                                min="0"
                                step="1"
                                class="form-input pl-10 @error('purchase_price') border-red-400 @enderror"
                            />
                        </div>
                        @error('purchase_price')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Harga Jual --}}
                    <div>
                        <label for="selling_price" class="form-label">
                            Harga Jual <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500 font-medium text-sm">Rp</span>
                            <input
                                type="number"
                                id="selling_price"
                                name="selling_price"
                                value="{{ old('selling_price', 0) }}"
                                min="0"
                                step="1"
                                class="form-input pl-10 @error('selling_price') border-red-400 @enderror"
                                required
                            />
                        </div>
                        @error('selling_price')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- Stock Card --}}
        <div class="bg-white rounded-xl border border-neutral-200 shadow-sm">
            <div class="px-6 py-4 border-b border-neutral-200">
                <h2 class="text-base font-semibold text-neutral-800">Informasi Stok</h2>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    {{-- Stok Minimum --}}
                    <div>
                        <label for="minimum_stock" class="form-label">Stok Minimum</label>
                        <input
                            type="number"
                            id="minimum_stock"
                            name="minimum_stock"
                            value="{{ old('minimum_stock', 0) }}"
                            min="0"
                            class="form-input @error('minimum_stock') border-red-400 @enderror"
                        />
                        <p class="mt-1 text-xs text-neutral-500">Notifikasi stok menipis akan muncul di bawah nilai ini</p>
                        @error('minimum_stock')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Satuan --}}
                    <div>
                        <label for="unit" class="form-label">
                            Satuan <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="unit"
                            name="unit"
                            value="{{ old('unit', 'pcs') }}"
                            list="unit-presets"
                            placeholder="Contoh: pcs, kg, liter"
                            class="form-input @error('unit') border-red-400 @enderror"
                            required
                        />
                        <datalist id="unit-presets">
                            <option value="pcs">
                            <option value="kg">
                            <option value="liter">
                            <option value="botol">
                            <option value="karung">
                            <option value="kotak">
                            <option value="lembar">
                            <option value="rim">
                            <option value="pak">
                            <option value="box">
                        </datalist>
                        @error('unit')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Info Note --}}
                <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                    </svg>
                    <p class="text-sm text-blue-800">
                        <span class="font-medium">Catatan:</span> Stok awal dapat ditambahkan melalui menu
                        <strong>Transaksi Stok</strong> setelah produk berhasil dibuat. Stok awal produk baru adalah 0.
                    </p>
                </div>

            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-3 justify-end">
            <a href="{{ route('products.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Produk
            </button>
        </div>

    </form>

</x-layouts.app>
