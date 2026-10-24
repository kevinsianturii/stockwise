<x-layouts.app title="Tambah Pengguna Baru">
    <div class="max-w-xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Tambah Pengguna Baru</h1>
                <p class="text-sm text-neutral-500 mt-0.5">Daftarkan akun staf atau administrator baru ke sistem.</p>
            </div>
            <a href="{{ route('users.index') }}" class="btn-secondary !text-xs !py-1.5">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali
            </a>
        </div>

        <div class="card p-6">
            <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="form-label" for="name">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Ahmad Rizky" class="form-input">
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label" for="email">Alamat Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="ahmad@tokobijak.id" class="form-input">
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label" for="role">Peran / Hak Akses <span class="text-red-500">*</span></label>
                    <select name="role" id="role" class="form-input" required>
                        <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staf Gudang (Hanya Mutasi & Katalog)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator (Akses Penuh)</option>
                    </select>
                    @error('role')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label" for="password">Kata Sandi <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" required class="form-input" placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required class="form-input">
                    </div>
                </div>

                <div class="pt-4 border-t border-neutral-100 flex items-center justify-end gap-3">
                    <a href="{{ route('users.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
