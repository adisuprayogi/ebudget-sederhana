<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Detail Notifikasi</h1>
                <p class="text-secondary-600 mt-1">Informasi lengkap notifikasi</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('notifications.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <!-- Notification Card -->
        <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
            <div class="flex items-start">
                <!-- Icon based on type -->
                <div class="flex-shrink-0 mr-6">
                    @if($notification->type === 'success')
                        <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    @elseif($notification->type === 'warning')
                        <div class="w-16 h-16 rounded-full bg-amber-100 flex items-center justify-center">
                            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    @elseif($notification->type === 'error')
                        <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    @else
                        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-secondary-900">{{ $notification->title }}</h2>
                        @if($notification->is_read)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-secondary-100 text-secondary-700">Sudah Dibaca</span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-700">Belum Dibaca</span>
                        @endif
                    </div>

                    <p class="text-lg text-secondary-700 mb-6">{{ $notification->message }}</p>

                    <!-- Type Badge -->
                    <div class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold
                        @if($notification->type === 'success') bg-green-100 text-green-700
                        @elseif($notification->type === 'warning') bg-amber-100 text-amber-700
                        @elseif($notification->type === 'error') bg-red-100 text-red-700
                        @else bg-blue-100 text-blue-700
                        @endif">
                        {{ ucfirst($notification->type) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Entity -->
        @if($notification->notifiable)
            <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
                <h3 class="text-lg font-semibold text-secondary-900 mb-4">Entitas Terkait</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-secondary-500">Tipe Entitas</label>
                        <div class="mt-1 font-medium text-secondary-900">{{ $notification->notifiable_type }}</div>
                    </div>
                    <div>
                        <label class="text-sm text-secondary-500">ID Entitas</label>
                        <div class="mt-1 font-medium text-secondary-900">#{{ $notification->notifiable_id }}</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Timestamps -->
        <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
            <h3 class="text-lg font-semibold text-secondary-900 mb-4">Waktu</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-secondary-500">Dibuat</label>
                    <div class="mt-1 text-secondary-900">{{ \Carbon\Carbon::parse($notification->created_at)->format('d F Y, H:i') }}</div>
                    <div class="text-xs text-secondary-500">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</div>
                </div>
                @if($notification->read_at)
                    <div>
                        <label class="text-sm text-secondary-500">Dibaca</label>
                        <div class="mt-1 text-secondary-900">{{ \Carbon\Carbon::parse($notification->read_at)->format('d F Y, H:i') }}</div>
                        <div class="text-xs text-secondary-500">{{ \Carbon\Carbon::parse($notification->read_at)->diffForHumans() }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Link Action -->
        @if($notification->link)
            <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
                <h3 class="text-lg font-semibold text-secondary-900 mb-4">Tautan Terkait</h3>
                <a href="{{ $notification->link }}" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Buka Tautan
                </a>
            </div>
        @endif

        <!-- Actions -->
        <div class="bg-white rounded-2xl shadow-soft p-8">
            <h3 class="text-lg font-semibold text-secondary-900 mb-4">Aksi</h3>
            <div class="flex flex-wrap items-center gap-4">
                @if($notification->is_read)
                    <form method="POST" action="{{ route('notifications.mark-unread', $notification) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Tandai Belum Dibaca
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('notifications.mark-read', $notification) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Tandai Sudah Dibaca
                        </button>
                    </form>
                @endif

                <form method="POST" action="{{ route('notifications.destroy', $notification) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Notifikasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
