<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail User</h1>
                <p class="text-gray-600 mt-1">{{ $user->full_name }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900">
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
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl font-bold text-blue-600">{{ strtoupper(substr($user->full_name, 0, 1)) }}</span>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-gray-900">{{ $user->full_name }}</h2>
                        <p class="text-gray-500">{{ $user->username }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                        @if($user->is_active) bg-green-100 text-green-700 @else bg-red-100 text-red-700 @endif">
                        {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
            </div>

            <!-- Details -->
            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Username</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $user->username }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Dibuat Tanggal</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Role + Division Combinations -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Role & Divisi</h3>
                @php $combinations = $user->roleDivisiCombinations(); @endphp
                @if($combinations->count() > 0)
                    <div class="space-y-3">
                        @foreach($combinations as $combo)
                            @php
                                $role = App\Models\Role::find($combo->role_id);
                                $divisi = $combo->divisi_id ? App\Models\Divisi::find($combo->divisi_id) : null;
                            @endphp
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $role->name }}</p>
                                        @if($divisi)
                                            <p class="text-sm text-gray-500">{{ $divisi->nama_divisi }}</p>
                                        @else
                                            <p class="text-sm text-gray-400">Tanpa Divisi</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($combo->is_primary)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                            Utama
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">User belum memiliki role atau divisi.</p>
                @endif
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Edit User
                </a>
                @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline" onsubmit="return confirm('Yakin ingin mengubah status user ini?');">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="px-4 py-2 {{ $user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg">
                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
