<x-layouts.app title="Catat Mutasi Stok">
    <div class="max-w-2xl mx-auto space-y-6" x-data="{
        type: '{{ $type ?? 'in' }}',
        selectedProduct: null,
        products: @js($products),
        currentStock: 0,
        currentUnit: 'pcs',
        onProductChange(event) {
            const id = parseInt(event.target.value);
            const found = this.products.find(p => p.id === id);
            if (found) {
                this.currentStock = found.current_stock;
                this.currentUnit = found.unit;
            } else {
                this.currentStock = 0;
                this.currentUnit = 'pcs';
            }
        }
    }">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Catat Mutasi Stok</h1>
                <p class="text-sm text-neutral-500 mt-0.5">Input transaksi barang masuk atau keluar gudang.</p>
            </div>
            <a href="{{ route('stock-movements.index') }}" class="btn-secondary !text-xs !py-1.5">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali
            </a>
        </div>

        {{-- Type Switcher Tabs --}}
        <div class="flex rounded-lg bg-neutral-100 p-1 border border-neutral-200">
            <a
                href="{{ route('stock-movements.create', ['type' => 'in', 'product_id' => request('product_id')]) }}"
                class="flex-1 py-2 text-center text-sm font-semibold rounded-md transition-colors flex items-center justify-center gap-2 {{ ($type ?? 'in') === 'in' ? 'bg-white text-[#2D6A4F] shadow-sm' : 'text-neutral-600 hover:text-neutral-900' }}"
            >
                <i data-lucide="arrow-down-left" class="w-4 h-4"></i>
                Barang Masuk (Stok Bertambah)
            </a>
            <a
                href="{{ route('stock-movements.create', ['type' => 'out', 'product_id' => request('product_id')]) }}"
                class="flex-1 py-2 text-center text-sm font-semibold rounded-md transition-colors flex items-center justify-center gap-2 {{ ($type ?? 'in') === 'out' ? 'bg-white text-red-600 shadow-sm' : 'text-neutral-600 hover:text-neutral-900' }}"
            >
                <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                Barang Keluar (Stok Berkurang)
            </a>
        </div>

        {{-- Form Container --}}
        <div class="card p-6">
            @if(($type ?? 'in') === 'in')
                {{-- Form Barang Masuk --}}
                <form method="POST" action="{{ route('stock-movements.store-in') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="form-label" for="product_id">Pilih Produk <span class="text-red-500">*</span></label>
                        <select name="product_id" id="product_id" @change="onProductChange($event)" class="form-input" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" {{ (old('product_id', $selectedProduct?->id) == $p->id) ? 'selected' : '' }}>
                                    {{ $p->name }} (SKU: {{ $p->sku }}) — Stok: {{ $p->current_stock }} {{ $p->unit }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label" for="quantity">Jumlah Masuk <span class="text-red-500">*</span></label>
                            <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity', 1) }}" class="form-input" required>
                            @error('quantity')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label" for="unit_cost">Harga Beli Satuan (Rp) <span class="text-xs text-neutral-400 font-normal">Opsional</span></label>
                            <input type="number" name="unit_cost" id="unit_cost" min="0" step="100" value="{{ old('unit_cost') }}" placeholder="Opsional" class="form-input">
                            @error('unit_cost')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="form-label" for="occurred_at">Tanggal Transaksi</label>
                        <input type="date" name="occurred_at" id="occurred_at" value="{{ old('occurred_at', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" class="form-input">
                        @error('occurred_at')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label" for="notes">Catatan / Keterangan <span class="text-xs text-neutral-400 font-normal">Opsional</span></label>
                        <textarea name="notes" id="notes" rows="3" placeholder="Contoh: Penerimaan supplier CV Berkah, No Surat Jalan SJ-001" class="form-input">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-3 border-t border-neutral-100 flex items-center justify-end gap-3">
                        <a href="{{ route('stock-movements.index') }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-primary">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            Simpan Barang Masuk
                        </button>
                    </div>
                </form>
            @else
                {{-- Form Barang Keluar --}}
                <form method="POST" action="{{ route('stock-movements.store-out') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="form-label" for="product_id">Pilih Produk <span class="text-red-500">*</span></label>
                        <select name="product_id" id="product_id" @change="onProductChange($event)" class="form-input" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" {{ (old('product_id', $selectedProduct?->id) == $p->id) ? 'selected' : '' }}>
                                    {{ $p->name }} (SKU: {{ $p->sku }}) — Stok Tersedia: {{ $p->current_stock }} {{ $p->unit }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label" for="quantity">Jumlah Keluar <span class="text-red-500">*</span></label>
                            <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity', 1) }}" class="form-input" required>
                            @error('quantity')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label" for="occurred_at">Tanggal Transaksi</label>
                            <input type="date" name="occurred_at" id="occurred_at" value="{{ old('occurred_at', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" class="form-input">
                            @error('occurred_at')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="form-label" for="notes">Alasan / Catatan Pengeluaran <span class="text-xs text-neutral-400 font-normal">Opsional</span></label>
                        <textarea name="notes" id="notes" rows="3" placeholder="Contoh: Penjualan toko, barang rusak, sample promosi" class="form-input">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="p-3 rounded-lg bg-amber-50 border border-amber-200 text-xs text-amber-800 flex items-start gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-amber-600 shrink-0 mt-0.5"></i>
                        <span>Sistem otomatis menolak transaksi jika jumlah keluar melebihi stok yang tersedia saat ini (tidak diperbolehkan stok negatif).</span>
                    </div>

                    <div class="pt-3 border-t border-neutral-100 flex items-center justify-end gap-3">
                        <a href="{{ route('stock-movements.index') }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-danger">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            Simpan Barang Keluar
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</x-layouts.app>
