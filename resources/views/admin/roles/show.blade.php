<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Role</h1>
                <p class="text-gray-600 mt-1">{{ $role->name }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.roles.edit', $role) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Edit Role
                </a>
                <a href="{{ route('admin.roles.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl font-bold text-purple-600">{{ strtoupper(substr($role->name, 0, 1)) }}</span>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-gray-900">{{ $role->name }}</h2>
                        @if($role->name === 'superadmin')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                Full Access
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $role->description ?? '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Permissions</dt>
                        <dd class="mt-1">
                            @if($role->name === 'superadmin')
                                <code class="px-2 py-1 bg-gray-100 rounded text-sm">*</code>
                                <span class="text-sm text-gray-600 ml-2">(Full Access)</span>
                            @else
                                @if(!empty($role->permissions))
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($role->permissions as $permission)
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                                {{ $permission }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Jumlah User</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ \App\Models\User::where('role_id', $role->id)->count() }} user
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Actions -->
            @if($role->name !== 'superadmin')
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                    <a href="{{ route('admin.roles.edit', $role) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Edit Role
                    </a>
                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus role ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Hapus Role
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
