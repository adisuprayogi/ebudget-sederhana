<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Notifikasi</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $notifications->total() }} notifikasi • {{ $unreadCount }} belum dibaca</p>
            </div>
            <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="inline">
                @csrf
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Tandai Semua Dibaca
                </button>
            </form>
        </div>
    </x-slot>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $notifications->total() }}</p>
                    <p class="text-xs text-gray-500">Total Notifikasi</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $unreadCount }}</p>
                    <p class="text-xs text-gray-500">Belum Dibaca</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $notifications->total() - $unreadCount }}</p>
                    <p class="text-xs text-gray-500">Sudah Dibaca</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-cyan-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ \App\Models\Notification::whereDate('created_at', today())->count() }}</p>
                    <p class="text-xs text-gray-500">Hari Ini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl border border-blue-100 p-3 mb-4">
        <form method="GET" action="{{ route('notifications.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="w-full md:min-w-[140px] md:w-auto">
                <select name="is_read" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Status</option>
                    <option value="false" {{ request('is_read') === 'false' ? 'selected' : '' }}>Belum Dibaca</option>
                    <option value="true" {{ request('is_read') === 'true' ? 'selected' : '' }}>Sudah Dibaca</option>
                </select>
            </div>
            <div class="w-full md:min-w-[140px] md:w-auto">
                <select name="type" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Tipe</option>
                    <option value="info" {{ request('type') === 'info' ? 'selected' : '' }}>Info</option>
                    <option value="success" {{ request('type') === 'success' ? 'selected' : '' }}>Success</option>
                    <option value="warning" {{ request('type') === 'warning' ? 'selected' : '' }}>Warning</option>
                    <option value="error" {{ request('type') === 'error' ? 'selected' : '' }}>Error</option>
                </select>
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                @if(request()->hasAny(['is_read', 'type']))
                    <a href="{{ route('notifications.index') }}" class="flex-1 md:flex-none inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
        @if($notifications->count() > 0)
            <ul class="divide-y divide-blue-50">
                @foreach($notifications as $notification)
                    <li class="transition-colors {{ !$notification->is_read ? 'bg-blue-50/50' : '' }} hover:bg-blue-50/50">
                        <a href="{{ route('notifications.show', $notification) }}" class="block px-3 md:px-5 py-3 md:py-4">
                            <div class="flex items-start gap-3">
                                <!-- Icon -->
                                <div class="flex-shrink-0">
                                    @if($notification->type === 'success')
                                        <div class="w-9 h-9 md:w-10 md:h-10 bg-emerald-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    @elseif($notification->type === 'warning')
                                        <div class="w-9 h-9 md:w-10 md:h-10 bg-amber-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                    @elseif($notification->type === 'error')
                                        <div class="w-9 h-9 md:w-10 md:h-10 bg-red-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-9 h-9 md:w-10 md:h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                                <h3 class="font-semibold text-gray-900 text-sm truncate">{{ $notification->title }}</h3>
                                                @if(!$notification->is_read)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 flex-shrink-0">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                        <span class="hidden sm:inline">Baru</span>
                                                        <span class="sm:hidden">New</span>
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 line-clamp-2">{{ Str::limit($notification->message, 150) }}</p>
                                        </div>
                                        <div class="flex flex-row sm:flex-col items-end gap-2 flex-shrink-0">
                                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $notification->created_at->diffForHumans() }}</span>
                                            @if($notification->type === 'success')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-700">Success</span>
                                            @elseif($notification->type === 'warning')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-700">Warning</span>
                                            @elseif($notification->type === 'error')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700">Error</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">Info</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="px-3 md:px-5 py-12 md:py-16 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <p class="text-gray-700 font-medium">Tidak ada notifikasi</p>
                    <p class="text-gray-400 text-sm mt-1">Anda belum memiliki notifikasi saat ini</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 text-sm">
            <span class="text-gray-500 text-center md:text-left">
                <span class="hidden md:inline">Menampilkan {{ $notifications->firstItem() ?? 0 }}–{{ $notifications->lastItem() ?? 0 }} dari {{ $notifications->total() }}</span>
                <span class="md:hidden">{{ $notifications->total() }} notifikasi</span>
            </span>
            {{ $notifications->appends(['is_read' => request('is_read'), 'type' => request('type')])->links() }}
        </div>
    @endif
</x-app-layout>
