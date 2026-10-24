<x-layouts.app title="Edit Pengguna">
    <div class="max-w-xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Edit Pengguna</h1>
                <p class="text-sm text-neutral-500 mt-0.5">{{ $user->name }} ({{ $user->email }})</p>
            </div>
            <a href="{{ route('users.index') }}" class="btn-secondary !text-xs !py-1.5">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali
            </a>
        </div>

        <div class="card p-6">
            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="form-label" for="name">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="form-input">
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label" for="email">Alamat Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="form-input">
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label" for="role">Peran / Hak Akses <span class="text-red-500">*</span></label>
                    <select name="role" id="role" class="form-input" required>
                        <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staf Gudang</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                    @error('role')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                @if($user->id !== auth()->id())
                    <div class="pt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="rounded border-neutral-300 text-[#2D6A4F] focus:ring-[#2D6A4F]">
                            <span class="text-sm font-medium text-neutral-800">Status Akun Aktif</span>
                        </label>
                        <p class="text-xs text-neutral-400 mt-0.5">Jika tidak dicentang, pengguna tidak dapat masuk ke sistem.</p>
                    </div>
                @endif

                <div class="pt-4 border-t border-neutral-200">
                    <p class="text-xs font-semibold text-neutral-600 mb-2">Ubah Kata Sandi (Kosongkan jika tidak ingin mengubah)</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label text-xs" for="password">Kata Sandi Baru</label>
                            <input type="password" name="password" id="password" class="form-input" placeholder="Minimal 8 karakter">
                            @error('password')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label text-xs" for="password_confirmation">Ulangi Kata Sandi</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-input">
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-neutral-100 flex items-center justify-end gap-3">
                    <a href="{{ route('users.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
