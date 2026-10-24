<x-layouts.app title="Edit Produk">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-neutral-500 mb-4">
        <a href="{{ route('products.index') }}" class="hover:text-[#2D6A4F] transition-colors">Produk</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('products.show', $product) }}" class="hover:text-[#2D6A4F] transition-colors truncate max-w-xs">{{ $product->name }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-neutral-800 font-medium">Edit</span>
    </nav>

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-800">Edit Produk</h1>
        <p class="text-sm text-neutral-600 mt-1">Perbarui informasi produk <span class="font-medium text-neutral-800">{{ $product->name }}</span></p>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('products.update', $product) }}" class="space-y-6">
        @csrf
        @method('PUT')

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
                        value="{{ old('sku', $product->sku) }}"
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
                        value="{{ old('name', $product->name) }}"
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
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
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
                    >{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status Aktif --}}
                <div class="flex items-start gap-3">
                    <div class="flex items-center h-5 mt-0.5">
                        <input
                            type="checkbox"
                            id="is_active"
                            name="is_active"
                            value="1"
                            {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-neutral-300 text-[#2D6A4F] focus:ring-[#2D6A4F] focus:ring-offset-0"
                        />
                    </div>
                    <div>
                        <label for="is_active" class="text-sm font-medium text-neutral-800 cursor-pointer">Produk Aktif</label>
                        <p class="text-xs text-neutral-500 mt-0.5">Produk nonaktif tidak akan muncul dalam pilihan transaksi</p>
                    </div>
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
                                value="{{ old('purchase_price', $product->purchase_price) }}"
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
                                value="{{ old('selling_price', $product->selling_price) }}"
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

                {{-- Current Stock (READ ONLY) --}}
                <div class="bg-neutral-50 rounded-lg border border-neutral-200 p-4">
                    <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Stok Saat Ini (Baca Saja)</p>
                    @php
                        $stockColor = 'text-green-600';
                        if ($product->current_stock == 0) {
                            $stockColor = 'text-red-600';
                        } elseif ($product->current_stock <= $product->minimum_stock) {
                            $stockColor = 'text-amber-600';
                        }
                    @endphp
                    <p class="text-3xl font-bold {{ $stockColor }}">
                        {{ number_format($product->current_stock) }}
                        <span class="text-base font-normal text-neutral-500">{{ $product->unit }}</span>
                    </p>
                    <p class="text-xs text-neutral-500 mt-1">Stok tidak dapat diubah secara langsung dari form ini</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    {{-- Stok Minimum --}}
                    <div>
                        <label for="minimum_stock" class="form-label">Stok Minimum</label>
                        <input
                            type="number"
                            id="minimum_stock"
                            name="minimum_stock"
                            value="{{ old('minimum_stock', $product->minimum_stock) }}"
                            min="0"
                            class="form-input @error('minimum_stock') border-red-400 @enderror"
                        />
                        <p class="mt-1 text-xs text-neutral-500">Batas stok sebelum peringatan muncul</p>
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
                            value="{{ old('unit', $product->unit) }}"
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

                {{-- Note about changing stock --}}
                <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-lg p-3">
                    <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5C2.962 18.333 3.924 20 5.464 20z"/>
                    </svg>
                    <p class="text-sm text-amber-800">
                        <span class="font-medium">Perubahan stok</span> hanya dapat dilakukan melalui menu
                        <a href="{{ route('stock-movements.index') }}" class="underline font-medium hover:text-amber-900">Transaksi Stok</a>.
                        Stok yang tertera di atas adalah stok real-time saat ini.
                    </p>
                </div>

            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-3 justify-end">
            <a href="{{ route('products.show', $product) }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>

    </form>

</x-layouts.app>
