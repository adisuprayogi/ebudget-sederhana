<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
                    <p class="text-gray-500 text-sm mt-0.5">{{ $user->full_name }}</p>
                </div>
            </div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium rounded-xl shadow-sm transition-all duration-200 hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    @php
        $existingCombinations = $user->roleDivisiCombinations();
        $primaryIndex = $existingCombinations->search(fn($c) => $c->is_primary) ?? 0;
    @endphp

    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Main Form -->
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @csrf
            @method('PUT')

            <!-- Info Banner -->
            <div class="bg-gradient-to-r from-blue-50 to-orange-50 px-6 py-4 border-b border-blue-100">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-blue-900">Informasi User</p>
                        <p class="text-sm text-blue-700 mt-0.5">Edit data diri user di bawah ini</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Nama</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-all">
                        </div>
                        @error('name')
                            <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-all">
                        </div>
                        @error('username')
                            <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-all">
                        </div>
                        @error('email')
                            <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Full Name -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-all">
                        </div>
                        @error('full_name')
                            <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-3 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200 cursor-pointer hover:from-gray-100 hover:to-gray-150 transition-all">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-orange-500">
                            <div class="flex-1">
                                <span class="block text-sm font-semibold text-gray-900">User Aktif</span>
                                <span class="block text-xs text-gray-600 mt-0.5">Aktifkan user untuk dapat login ke sistem</span>
                            </div>
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                @if($user->is_active)
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @endif
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Role & Divisi Section -->
            <div class="border-t border-gray-200">
                <div class="bg-gradient-to-r from-orange-50 to-orange-50 px-6 py-4 border-b border-orange-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 01-.806 1.946 3.42 3.42 0 00-3.138 3.138 3.42 3.42 0 01-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 01-1.946-.806 3.42 3.42 0 00-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 01.806-1.946 3.42 3.42 0 003.138-3.138z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-orange-900">Role & Divisi</p>
                                <p class="text-sm text-orange-700 mt-0.5">Tentukan hak akses dan divisi user</p>
                            </div>
                        </div>
                        <button type="button" id="add-role-divisi" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-xl shadow-lg shadow-orange-500/30 transition-all duration-200 hover:scale-[1.02]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div id="role-divisi-container" class="space-y-3">
                        <!-- Role & Divisi combinations will be added here via JavaScript -->
                    </div>
                    @error('role_divisi')
                        <p class="text-sm text-red-600 mt-3 flex items-center gap-1.5 bg-red-50 px-4 py-3 rounded-xl border border-red-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 font-medium rounded-xl shadow-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-lg shadow-blue-500/30 transition-all duration-200 hover:scale-[1.02]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>

        <!-- Change Password Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-rose-50 to-pink-50 px-6 py-4 border-b border-rose-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-rose-900">Ganti Password</p>
                        <p class="text-sm text-rose-700 mt-0.5">Reset password user jika diperlukan</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Password Baru</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" name="password" minlength="8"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-all" placeholder="Minimal 8 karakter">
                        </div>
                        @error('password')
                            <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Konfirmasi Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <input type="password" name="password_confirmation" minlength="8"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-all">
                        </div>
                        @error('password_confirmation')
                            <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="mt-5 flex items-center justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-700 hover:to-pink-700 text-white font-medium rounded-xl shadow-lg shadow-rose-500/30 transition-all duration-200 hover:scale-[1.02]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Ganti Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const roles = @json($roles);
        const divisis = @json($divisis);
        const existingCombinations = @json($existingCombinations);
        const primaryIndex = {{ $primaryIndex }};
        let roleDivisiCount = 0;

        function addRoleDivisiCombo(existingData = null) {
            const container = document.getElementById('role-divisi-container');
            const index = roleDivisiCount++;

            const div = document.createElement('div');
            div.className = 'role-divisi-row flex items-start gap-3 p-5 bg-gradient-to-r from-orange-50 to-orange-50 rounded-xl border border-orange-200 transition-all hover:shadow-md';
            div.dataset.index = index;

            const isSelectedPrimary = existingData && existingCombinations.indexOf(existingData) === primaryIndex;

            div.innerHTML = `
                <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Radio for Primary -->
                    <div class="flex items-center">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="primary_index" value="${index}" ${isSelectedPrimary ? 'checked' : ''} required
                                class="w-5 h-5 text-orange-600 border-gray-300 focus:ring-orange-500">
                            <div>
                                <span class="block text-sm font-semibold text-gray-900">Utama</span>
                                <span class="block text-xs text-gray-500">Role primary</span>
                            </div>
                        </label>
                    </div>

                    <!-- Role Select -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Role</label>
                        <select name="role_divisi[${index}][role_id]" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-orange-500 focus:border-orange-500 bg-white">
                            <option value="">-- Pilih Role --</option>
                            ${roles.map(r => `<option value="${r.id}" ${existingData && existingData.role_id == r.id ? 'selected' : ''}>${r.name}</option>`).join('')}
                        </select>
                    </div>

                    <!-- Divisi Select -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Divisi</label>
                        <select name="role_divisi[${index}][divisi_id]"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-orange-500 focus:border-orange-500 bg-white">
                            <option value="">-- Tanpa Divisi --</option>
                            ${divisis.map(d => `<option value="${d.id}" ${existingData && existingData.divisi_id == d.id ? 'selected' : ''}>${d.nama_divisi}</option>`).join('')}
                        </select>
                    </div>
                </div>

                <button type="button" onclick="removeRoleDivisi(this)" class="p-2.5 text-red-500 hover:text-red-700 hover:bg-red-100 rounded-xl transition-all" title="Hapus">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;

            container.appendChild(div);
        }

        function removeRoleDivisi(button) {
            const row = button.closest('.role-divisi-row');
            const container = document.getElementById('role-divisi-container');

            if (container.children.length <= 1) {
                alert('Minimal satu role & divisi harus diisi');
                return;
            }

            const wasChecked = row.querySelector('input[type="radio"]:checked');
            row.remove();

            // If we removed the checked one, check the first remaining
            if (wasChecked) {
                const firstRadio = container.querySelector('input[type="radio"]');
                if (firstRadio) {
                    firstRadio.checked = true;
                }
            }
        }

        // Initialize with existing combinations
        document.addEventListener('DOMContentLoaded', function() {
            if (existingCombinations.length > 0) {
                existingCombinations.forEach(combo => {
                    addRoleDivisiCombo(combo);
                });
            } else {
                addRoleDivisiCombo();
            }

            document.getElementById('add-role-divisi').addEventListener('click', function() {
                addRoleDivisiCombo();
            });
        });
    </script>
</x-app-layout>
