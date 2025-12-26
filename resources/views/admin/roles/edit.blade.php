<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Role</h1>
                <p class="text-gray-600 mt-1">{{ $role->name }}</p>
            </div>
            <a href="{{ route('admin.roles.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8">
        <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="bg-white rounded-lg shadow-sm p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Role</label>
                    <input type="text" name="name" value="{{ old('name', $role->name) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        @if($role->name === 'superadmin') readonly @endif>
                    @if($role->name === 'superadmin')
                        <p class="text-xs text-gray-500 mt-1">Nama role superadmin tidak dapat diubah</p>
                    @endif
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $role->description) }}</textarea>
                    @error('description')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Permissions -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Permissions</label>
                    @if($role->name === 'superadmin')
                        <p class="text-sm text-gray-700 bg-gray-100 p-3 rounded-lg">
                            <code>*</code> (Full Access)
                        </p>
                        <input type="hidden" name="permissions" value="*">
                    @else
                        <p class="text-sm text-gray-500 mb-2">Pisahkan dengan koma (,)</p>
                        <textarea name="permissions" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono text-sm">{{ old('permissions', is_array($role->permissions) ? implode(', ', $role->permissions) : $role->permissions) }}</textarea>
                        @error('permissions')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    @endif
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
