<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Manajemen Role</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $roles->total() }} role • {{ \App\Models\User::count() }} total user</p>
            </div>
            <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Role
            </a>
        </div>
    </x-slot>

    <!-- Quick Stats -->
    <div class="grid grid-cols-4 gap-3 mb-4">
        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $roles->total() }}</p>
                    <p class="text-xs text-gray-500">Total Role</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-violet-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ \App\Models\Role::where('name', 'superadmin')->count() }}</p>
                    <p class="text-xs text-gray-500">Superadmin</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-cyan-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ \App\Models\Role::whereNotIn('name', ['superadmin'])->count() }}</p>
                    <p class="text-xs text-gray-500">Role User</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ \App\Models\User::count() }}</p>
                    <p class="text-xs text-gray-500">Total User</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-blue-50 border-b border-blue-100">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Role</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Deskripsi</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Permissions</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-blue-700 uppercase">User</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-50">
                @forelse($roles as $role)
                    @php
                        $userCount = \App\Models\User::where('role_id', $role->id)->count();
                    @endphp
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 {{ $role->name == 'superadmin' ? 'bg-violet-500' : 'bg-blue-500' }} rounded-lg flex items-center justify-center flex-shrink-0">
                                    @if($role->name == 'superadmin')
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">{{ $role->display_name ?? $role->name }}</p>
                                    <p class="text-xs text-gray-400 font-mono">{{ $role->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-sm text-gray-600 max-w-[250px]">{{ $role->description ?? '-' }}</p>
                        </td>
                        <td class="px-5 py-4">
                            @if($role->name === 'superadmin')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-violet-100 text-violet-700">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Full Access
                                </span>
                            @else
                                <div class="flex flex-wrap gap-1">
                                    @if(!empty($role->permissions))
                                        @foreach(array_slice($role->permissions, 0, 3) as $permission)
                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded bg-blue-100 text-blue-700">
                                                {{ $permission }}
                                            </span>
                                        @endforeach
                                        @if(count($role->permissions) > 3)
                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded bg-blue-200 text-blue-800">
                                                +{{ count($role->permissions) - 3 }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $userCount > 0 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-400' }} text-sm font-bold">
                                {{ $userCount }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.roles.edit', $role) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                @if($role->name !== 'superadmin')
                                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="inline" onsubmit="return confirm('Hapus role ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center text-center">
                                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p class="text-gray-700 font-medium">Belum ada role</p>
                                <p class="text-gray-400 text-sm mt-1">Tambahkan role untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($roles->hasPages())
        <div class="mt-4 flex items-center justify-between text-sm">
            <span class="text-gray-500">
                Menampilkan {{ $roles->firstItem() ?? 0 }}–{{ $roles->lastItem() ?? 0 }} dari {{ $roles->total() }}
            </span>
            {{ $roles->links() }}
        </div>
    @endif
</x-app-layout>
