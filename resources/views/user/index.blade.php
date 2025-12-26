<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Manajemen Pengguna</h1>
                <p class="text-secondary-600 mt-1">Kelola pengguna dan hak akses sistem</p>
            </div>
            <div class="flex items-center space-x-3">
                @if($permissions['create'] ?? false)
                    <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Pengguna
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-soft p-6 mb-8">
            <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nama, email, username..." class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Role</label>
                    <select name="role_id" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Role</option>
                        @foreach($filterOptions['roles'] ?? [] as $role)
                            <option value="{{ $role->id }}" {{ ($filters['role_id'] ?? '') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Divisi</label>
                    <select name="divisi_id" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Divisi</option>
                        @foreach($filterOptions['divisis'] ?? [] as $divisi)
                            <option value="{{ $divisi->id }}" {{ ($filters['divisi_id'] ?? '') == $divisi->id ? 'selected' : '' }}>{{ $divisi->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Status</label>
                    <select name="is_active" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua</option>
                        <option value="1" {{ ($filters['is_active'] ?? '') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ ($filters['is_active'] ?? '') === '0' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Users List -->
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-secondary-50 border-b border-secondary-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Pengguna</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Divisi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary-100">
                        @forelse($users ?? [] as $user)
                            <tr class="hover:bg-secondary-50 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                                            @if($user->avatar)
                                                <img src="{{ asset('storage/' . $user->avatar) }}" class="w-10 h-10 rounded-full object-cover" alt="{{ $user->full_name }}">
                                            @else
                                                <span class="text-primary-600 font-semibold text-sm">{{ substr($user->full_name, 0, 2) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-medium text-secondary-900">{{ $user->full_name }}</div>
                                            <div class="text-sm text-secondary-500">@{{ $user->username ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-secondary-700">{{ $user->email }}</div>
                                    @if($user->phone)
                                        <div class="text-sm text-secondary-500">{{ $user->phone }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                        {{ $user->role->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-secondary-700">{{ $user->divisi->nama_divisi ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->is_active)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">Non-Aktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('users.show', $user) }}" class="p-2 text-secondary-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Lihat">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        @if($permissions['edit'] ?? false)
                                            <a href="{{ route('users.edit', $user) }}" class="p-2 text-secondary-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        @endif
                                        @if($permissions['reset_password'] ?? false && $user->id !== auth()->id())
                                            <button type="button" x-data="{ open: false }" @click="open = true" class="p-2 text-secondary-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Reset Password">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-secondary-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <p class="text-secondary-500">Belum ada pengguna</p>
                                        @if($permissions['create'] ?? false)
                                            <a href="{{ route('users.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                                                Tambah Pengguna Baru
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($users) && $users->hasPages())
                <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div x-data="{ show: false, userId: null }" x-show="show" x-transition class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false"></div>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST" action="{{ route('users.reset-password', ':id') }}" x-bind:action="userId ? '{{ route('users.reset-password', '') }}/' + userId : ''">
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
            document.querySelector('[x-data]').__x.$data.userId = userId;
            document.querySelector('[x-data]').__x.$data.show = true;
        }

        document.querySelectorAll('[title="Reset Password"]').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.closest('tr').querySelector('a[href^="{{ route('users.show', ['']) }}"]').href.split('/').pop();
                openResetModal(userId);
            });
        });
    </script>
</x-app-layout>
