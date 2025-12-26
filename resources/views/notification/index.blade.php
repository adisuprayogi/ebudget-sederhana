<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Notifikasi</h1>
                <p class="text-secondary-600 mt-1">Kelola notifikasi dan update terbaru</p>
            </div>
            <div class="flex items-center space-x-3">
                @if($unreadCount ?? 0 > 0)
                    <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Tandai Semua Dibaca
                        </button>
                    </form>
                @endif
                <div class="relative">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200" onclick="document.getElementById('bulk-actions-menu').classList.toggle('hidden')">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                        </svg>
                        Aksi Massal
                    </button>
                    <div id="bulk-actions-menu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-secondary-200 py-2 z-10">
                        <form method="POST" action="{{ route('notifications.mark-all-unread') }}" class="block">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-secondary-700 hover:bg-secondary-50">Tandai Semua Belum Dibaca</button>
                        </form>
                        <form method="POST" action="{{ route('notifications.destroy-read') }}" class="block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Hapus yang Sudah Dibaca</button>
                        </form>
                        <form method="POST" action="{{ route('notifications.destroy-all') }}" class="block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Hapus Semua Notifikasi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Total Notifikasi</div>
                <div class="text-2xl font-bold text-secondary-900">{{ $notifications->total() }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Belum Dibaca</div>
                <div class="text-2xl font-bold text-blue-600">{{ $unreadCount ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Sudah Dibaca</div>
                <div class="text-2xl font-bold text-secondary-600">{{ $notifications->total() - ($unreadCount ?? 0) }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6 cursor-pointer hover:shadow-medium transition-shadow" onclick="window.location.href='{{ route('notifications.index', ['type' => 'info']) }}'">
                <div class="text-sm text-secondary-500 mb-1">Info</div>
                <div class="text-2xl font-bold text-indigo-600">{{ ($filters['type'] ?? '') === 'info' ? $notifications->total() : '-' }}</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-soft p-6 mb-8">
            <form method="GET" action="{{ route('notifications.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Status Baca</label>
                    <select name="is_read" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="false" {{ ($filters['is_read'] ?? '') === 'false' ? 'selected' : '' }}>Belum Dibaca</option>
                        <option value="true" {{ ($filters['is_read'] ?? '') === 'true' ? 'selected' : '' }}>Sudah Dibaca</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Tipe</label>
                    <select name="type" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Tipe</option>
                        <option value="info" {{ ($filters['type'] ?? '') === 'info' ? 'selected' : '' }}>Info</option>
                        <option value="success" {{ ($filters['type'] ?? '') === 'success' ? 'selected' : '' }}>Success</option>
                        <option value="warning" {{ ($filters['type'] ?? '') === 'warning' ? 'selected' : '' }}>Warning</option>
                        <option value="error" {{ ($filters['type'] ?? '') === 'error' ? 'selected' : '' }}>Error</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Notifications List -->
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <div class="divide-y divide-secondary-100">
                @forelse($notifications ?? [] as $notification)
                    <div class="p-6 hover:bg-secondary-50 transition-colors duration-150 {{ !$notification->is_read ? 'bg-blue-50' : '' }}">
                        <div class="flex items-start">
                            <!-- Icon based on type -->
                            <div class="flex-shrink-0 mr-4">
                                @if($notification->type === 'success')
                                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                @elseif($notification->type === 'warning')
                                    <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                @elseif($notification->type === 'error')
                                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-secondary-900 {{ !$notification->is_read ? 'font-bold' : '' }}">
                                        {{ $notification->title }}
                                    </h3>
                                    <div class="flex items-center space-x-2 ml-4">
                                        @if(!$notification->is_read)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Baru</span>
                                        @endif
                                        <span class="text-xs text-secondary-500">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-sm text-secondary-700 line-clamp-2">{{ $notification->message }}</p>

                                <!-- Related Info -->
                                @if($notification->notifiable)
                                    <div class="mt-2 text-xs text-secondary-500">
                                        Terkait: {{ $notification->notifiable_type }}
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="mt-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        @if($notification->link)
                                            <a href="{{ $notification->link }}" class="inline-flex items-center px-3 py-1 text-sm text-primary-600 hover:bg-primary-50 rounded-lg transition-colors">
                                                Lihat Detail
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        @endif
                                        <a href="{{ route('notifications.show', $notification) }}" class="inline-flex items-center px-3 py-1 text-sm text-secondary-600 hover:bg-secondary-100 rounded-lg transition-colors">
                                            Buka
                                        </a>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        @if($notification->is_read)
                                            <form method="POST" action="{{ route('notifications.mark-unread', $notification) }}" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="p-2 text-secondary-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Tandai belum dibaca">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('notifications.mark-read', $notification) }}" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="p-2 text-secondary-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Tandai sudah dibaca">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('notifications.destroy', $notification) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-secondary-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-secondary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <p class="text-secondary-500">Tidak ada notifikasi</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if(isset($notifications) && $notifications->hasPages())
                <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200">
                    {{ $notifications->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
