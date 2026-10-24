<x-layouts.app title="Edit Supplier">
    <div class="max-w-2xl mx-auto space-y-6">
        {{-- Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('suppliers.show', $supplier) }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Supplier</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $supplier->name }}</p>
            </div>
        </div>

        {{-- Form --}}
        <div class="card">
            <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div>
                    <label for="name" class="form-label">
                        Nama Supplier <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $supplier->name) }}"
                        class="form-input w-full @error('name') border-red-500 @enderror"
                        placeholder="Nama perusahaan atau supplier"
                        required
                    />
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kontak Person --}}
                <div>
                    <label for="contact_person" class="form-label">Kontak Person</label>
                    <input
                        type="text"
                        id="contact_person"
                        name="contact_person"
                        value="{{ old('contact_person', $supplier->contact_person) }}"
                        class="form-input w-full @error('contact_person') border-red-500 @enderror"
                        placeholder="Nama penanggung jawab"
                    />
                    @error('contact_person')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Telepon & Email --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="form-label">Telepon</label>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $supplier->phone) }}"
                            class="form-input w-full @error('phone') border-red-500 @enderror"
                            placeholder="08xx-xxxx-xxxx"
                        />
                        @error('phone')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $supplier->email) }}"
                            class="form-input w-full @error('email') border-red-500 @enderror"
                            placeholder="email@supplier.com"
                        />
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="address" class="form-label">Alamat</label>
                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        class="form-input w-full @error('address') border-red-500 @enderror"
                        placeholder="Alamat lengkap supplier"
                    >{{ old('address', $supplier->address) }}</textarea>
                    @error('address')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div>
                    <label for="notes" class="form-label">Catatan</label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="3"
                        class="form-input w-full @error('notes') border-red-500 @enderror"
                        placeholder="Catatan tambahan tentang supplier (opsional)"
                    >{{ old('notes', $supplier->notes) }}</textarea>
                    @error('notes')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('suppliers.show', $supplier) }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
