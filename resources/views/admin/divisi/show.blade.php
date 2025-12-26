<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $divisi->nama_divisi }}</h1>
                <p class="text-gray-600 mt-1">{{ $divisi->kode_divisi }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.divisi.index') }}" class="text-gray-600 hover:text-gray-900">
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
                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl font-bold text-blue-600">{{ strtoupper(substr($divisi->nama_divisi, 0, 1)) }}</span>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-gray-900">{{ $divisi->nama_divisi }}</h2>
                        <p class="text-gray-500 font-mono">{{ $divisi->kode_divisi }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                        @if($divisi->is_active) bg-green-100 text-green-700 @else bg-red-100 text-red-700 @endif">
                        {{ $divisi->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>

            <!-- Details -->
            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $divisi->description ?? '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Jumlah User</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $divisi->users->count() }} user</dd>
                    </div>
                </dl>
            </div>

            <!-- Users in this division -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">User di Divisi Ini</h3>
                @if($divisi->users->count() > 0)
                    <div class="space-y-2">
                        @foreach($divisi->users as $user)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-bold text-blue-600">{{ strtoupper(substr($user->full_name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $user->full_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                    @if($user->is_active) bg-green-100 text-green-700 @else bg-red-100 text-red-700 @endif">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Belum ada user di divisi ini.</p>
                @endif
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.divisi.edit', $divisi) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Edit Divisi
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
