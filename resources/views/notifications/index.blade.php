<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Notifikasi</h1>
                <p class="text-gray-600 mt-1">Daftar notifikasi dan pesan untuk Anda</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm text-gray-500">Total Notifikasi</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $notifications->total() }}</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm text-gray-500">Sudah Dibaca</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $notifications->total() - $unreadCount }}</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm text-gray-500">Belum Dibaca</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $unreadCount }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('notifications.index') }}" class="flex flex-wrap gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="is_read" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua</option>
                        <option value="false" {{ request('is_read') === 'false' ? 'selected' : '' }}>Belum Dibaca</option>
                        <option value="true" {{ request('is_read') === 'true' ? 'selected' : '' }}>Sudah Dibaca</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                    <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Tipe</option>
                        <option value="info" {{ request('type') === 'info' ? 'selected' : '' }}>Info</option>
                        <option value="success" {{ request('type') === 'success' ? 'selected' : '' }}>Success</option>
                        <option value="warning" {{ request('type') === 'warning' ? 'selected' : '' }}>Warning</option>
                        <option value="error" {{ request('type') === 'error' ? 'selected' : '' }}>Error</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('notifications.index') }}" class="ml-2 px-4 py-2 text-gray-600 hover:text-gray-900">Reset</a>
                </div>
            </form>
        </div>

        <!-- Notifications List -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            @if($notifications->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($notifications as $notification)
                        <li class="hover:bg-gray-50 {{ !$notification->is_read ? 'bg-blue-50' : '' }}">
                            <a href="{{ route('notifications.show', $notification) }}" class="block px-6 py-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        @if($notification->type === 'success')
                                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'warning')
                                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'error')
                                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-medium text-gray-900">{{ $notification->title }}</h3>
                                            <div class="flex items-center gap-2">
                                                @if(!$notification->is_read)
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-800">Baru</span>
                                                @endif
                                                <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-600">{{ Str::limit($notification->message, 100) }}</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada notifikasi</h3>
                    <p class="mt-2 text-gray-500">Anda belum memiliki notifikasi saat ini.</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
