<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
                <p class="text-gray-600 mt-1">{{ $user->full_name }}</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
        </div>
    </x-slot>

    @php
        $existingCombinations = $user->roleDivisiCombinations();
        $primaryIndex = $existingCombinations->search(fn($c) => $c->is_primary) ?? 0;
    @endphp

    <div class="max-w-4xl mx-auto py-8">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-white rounded-lg shadow-sm p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Username -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Full Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">User Aktif</span>
                    </label>
                </div>
            </div>

            <!-- Role & Divisi Section -->
            <div class="mt-8 border-t border-gray-200 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Role & Divisi</h3>
                    <button type="button" id="add-role-divisi" class="px-3 py-1.5 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </button>
                </div>

                <div id="role-divisi-container" class="space-y-3">
                    <!-- Role & Divisi combinations will be added here via JavaScript -->
                </div>
                @error('role_divisi')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>

        <!-- Change Password Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ganti Password</h3>
            <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="password" minlength="8"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" minlength="8"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
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
            div.className = 'role-divisi-row flex items-start gap-3 p-4 bg-gray-50 rounded-lg';
            div.dataset.index = index;

            const isSelectedPrimary = existingData && existingCombinations.indexOf(existingData) === primaryIndex;

            div.innerHTML = `
                <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-3">
                    <!-- Radio for Primary -->
                    <div class="flex items-center">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="primary_index" value="${index}" ${isSelectedPrimary ? 'checked' : ''} required
                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-2 text-sm font-medium text-gray-900">Utama</span>
                        </label>
                    </div>

                    <!-- Role Select -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role_divisi[${index}][role_id]" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Role --</option>
                            ${roles.map(r => `<option value="${r.id}" ${existingData && existingData.role_id == r.id ? 'selected' : ''}>${r.name}</option>`).join('')}
                        </select>
                    </div>

                    <!-- Divisi Select -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                        <select name="role_divisi[${index}][divisi_id]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Tanpa Divisi --</option>
                            ${divisis.map(d => `<option value="${d.id}" ${existingData && existingData.divisi_id == d.id ? 'selected' : ''}>${d.nama_divisi}</option>`).join('')}
                        </select>
                    </div>
                </div>

                <button type="button" onclick="removeRoleDivisi(this)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Hapus">
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
