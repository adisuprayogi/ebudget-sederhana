<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Detail Pengguna</h1>
                <p class="text-secondary-600 mt-1">Informasi lengkap pengguna</p>
            </div>
            <div class="flex items-center space-x-3">
                @if($permissions['edit'] ?? false)
                    <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                @endif
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8">
        <!-- Profile Header -->
        <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
            <div class="flex items-start">
                <div class="w-24 h-24 bg-primary-100 rounded-full flex items-center justify-center mr-6">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-24 h-24 rounded-full object-cover" alt="{{ $user->full_name }}">
                    @else
                        <span class="text-primary-600 font-bold text-2xl">{{ substr($user->full_name, 0, 2) }}</span>
                    @endif
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-secondary-900">{{ $user->full_name }}</h2>
                            <p class="text-secondary-500">@{{ $user->username ?? '-' }}</p>
                        </div>
                        <div class="text-right">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-slate-100 text-slate-700">Non-Aktif</span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-4 flex items-center space-x-6">
                        <div class="flex items-center text-secondary-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $user->email }}
                        </div>
                        @if($user->phone)
                            <div class="flex items-center text-secondary-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $user->phone }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- User Information -->
                <div class="bg-white rounded-2xl shadow-soft p-8">
                    <h3 class="text-lg font-semibold text-secondary-900 mb-6">Informasi Pengguna</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm text-secondary-500">Role</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-700">
                                    {{ $user->role->name ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm text-secondary-500">Divisi</label>
                            <div class="mt-1 text-secondary-900">{{ $user->divisi->nama_divisi ?? '-' }}</div>
                        </div>
                        <div>
                            <label class="text-sm text-secondary-500">Terdaftar Sejak</label>
                            <div class="mt-1 text-secondary-900">{{ \Carbon\Carbon::parse($user->created_at)->format('d F Y') }}</div>
                        </div>
                        <div>
                            <label class="text-sm text-secondary-500">Terakhir Login</label>
                            <div class="mt-1 text-secondary-900">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d F Y, H:i') : '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Activity Summary -->
                <div class="bg-white rounded-2xl shadow-soft p-8">
                    <h3 class="text-lg font-semibold text-secondary-900 mb-6">Ringkasan Aktivitas</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-secondary-50 rounded-xl p-4">
                            <div class="text-sm text-secondary-500">Pengajuan</div>
                            <div class="text-xl font-bold text-secondary-900">{{ $activitySummary['total_pengajuan'] ?? 0 }}</div>
                        </div>
                        <div class="bg-secondary-50 rounded-xl p-4">
                            <div class="text-sm text-secondary-500">Approval</div>
                            <div class="text-xl font-bold text-secondary-900">{{ $activitySummary['total_approval'] ?? 0 }}</div>
                        </div>
                        <div class="bg-secondary-50 rounded-xl p-4">
                            <div class="text-sm text-secondary-500">LPJ</div>
                            <div class="text-xl font-bold text-secondary-900">{{ $activitySummary['total_lpj'] ?? 0 }}</div>
                        </div>
                        <div class="bg-secondary-50 rounded-xl p-4">
                            <div class="text-sm text-secondary-500">Login</div>
                            <div class="text-xl font-bold text-secondary-900">{{ $activitySummary['total_login'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Actions -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h3 class="text-lg font-semibold text-secondary-900 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        @if($permissions['edit'] ?? false)
                            <a href="{{ route('users.edit', $user) }}" class="flex items-center px-4 py-3 bg-secondary-50 hover:bg-secondary-100 rounded-xl transition-colors">
                                <svg class="w-5 h-5 mr-3 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Pengguna
                            </a>
                        @endif
                        @if($permissions['reset_password'] ?? false && $user->id !== auth()->id())
                            <button onclick="openResetModal({{ $user->id }})" class="w-full flex items-center px-4 py-3 bg-secondary-50 hover:bg-secondary-100 rounded-xl transition-colors">
                                <svg class="w-5 h-5 mr-3 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                Reset Password
                            </button>
                        @endif
                        @if($permissions['toggle_active'] ?? false && $user->id !== auth()->id())
                            <button onclick="toggleStatus({{ $user->id }})" class="w-full flex items-center px-4 py-3 bg-secondary-50 hover:bg-secondary-100 rounded-xl transition-colors">
                                <svg class="w-5 h-5 mr-3 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                                {{ $user->is_active ? 'Non-aktifkan' : 'Aktifkan' }}
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h3 class="text-lg font-semibold text-secondary-900 mb-4">Aktivitas Terbaru</h3>
                    <div class="space-y-3">
                        @if($user->pengajuanDan && $user->pengajuanDan->count() > 0)
                            @foreach($user->pengajuanDan->take(3) as $pengajuan)
                                <div class="text-sm">
                                    <div class="text-secondary-900">Pengajuan: {{ $pengajuan->uraian }}</div>
                                    <div class="text-secondary-500 text-xs">{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d M Y') }}</div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-secondary-500">Tidak ada aktivitas</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div x-data="{ show: false, userId: null }" x-show="show" x-transition class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false"></div>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST" action="{{ route('users.reset-password, '') }}/:id" x-bind:action="userId ? '{{ route('users.reset-password', '') }}/' + userId : ''">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-secondary-900 mb-4">Reset Password</h3>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Password Baru</label>
                            <input type="password" name="password" required minlength="8" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Minimal 8 karakter">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required minlength="8" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Ulangi password baru">
                        </div>
                    </div>
                    <div class="bg-secondary-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 sm:ml-3 sm:w-auto sm:text-sm">
                            Reset Password
                        </button>
                        <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-secondary-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-secondary-700 hover:bg-secondary-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openResetModal(userId) {
            const modal = document.querySelector('[x-data]');
            modal.__x.$data.userId = userId;
            modal.__x.$data.show = true;
        }

        function toggleStatus(userId) {
            if (!confirm('Apakah Anda yakin ingin mengubah status pengguna ini?')) return;

            fetch('{{ route('users.toggle-status', '') }}/' + userId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        }
    </script>
</x-app-layout>
