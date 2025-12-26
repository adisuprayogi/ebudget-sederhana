<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Edit Pengguna</h1>
                <p class="text-secondary-600 mt-1">Ubah data pengguna</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('users.show', $user) }}" class="inline-flex items-center px-4 py-2 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail
                </a>
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8">
        <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- User Information -->
            <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
                <h2 class="text-lg font-semibold text-secondary-900 mb-6">Informasi Pengguna</h2>

                <div class="space-y-6">
                    <!-- Avatar Upload -->
                    <div class="flex items-center">
                        <div class="w-20 h-20 bg-secondary-100 rounded-full flex items-center justify-center mr-6 overflow-hidden">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" class="w-20 h-20 rounded-full object-cover" alt="{{ $user->full_name }}">
                            @else
                                <span class="text-primary-600 font-semibold text-xl">{{ substr($user->full_name, 0, 2) }}</span>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Foto Profil</label>
                            <input type="file" name="avatar" accept="image/*" class="text-sm text-secondary-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                            <p class="text-xs text-secondary-500 mt-1">Kosongkan untuk tetap menggunakan foto yang ada</p>
                        </div>
                    </div>

                    <!-- Full Name -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Masukkan nama lengkap">
                        @error('full_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Username untuk login (opsional)">
                        @error('username')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="contoh@email.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">No. Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Role & Access -->
            <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
                <h2 class="text-lg font-semibold text-secondary-900 mb-6">Role & Akses</h2>

                <div class="space-y-6">
                    <!-- Role -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Role <span class="text-red-500">*</span></label>
                        <select name="role_id" required class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">Pilih Role</option>
                            @foreach($roles ?? [] as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Divisi -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Divisi</label>
                        <select name="divisi_id" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">Pilih Divisi (Opsional)</option>
                            @foreach($divisis ?? [] as $divisi)
                                <option value="{{ $divisi->id }}" {{ old('divisi_id', $user->divisi_id) == $divisi->id ? 'selected' : '' }}>{{ $divisi->nama_divisi }}</option>
                            @endforeach
                        </select>
                        @error('divisi_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="w-5 h-5 text-primary-600 border-secondary-300 rounded focus:ring-primary-500">
                        <label for="is_active" class="ml-3 text-sm font-medium text-secondary-700">Aktifkan pengguna</label>
                    </div>
                </div>
            </div>

            <!-- Password (Optional) -->
            <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
                <h2 class="text-lg font-semibold text-secondary-900 mb-6">Password (Opsional)</h2>
                <p class="text-sm text-secondary-500 mb-6">Kosongkan jika tidak ingin mengubah password</p>

                <div class="space-y-6">
                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Password Baru</label>
                        <input type="password" name="password" minlength="8" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" minlength="8" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('users.show', $user) }}" class="px-6 py-3 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
