<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Notifikasi</h1>
            </div>
            <a href="{{ route('notifications.index') }}" class="inline-flex items-center px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        @if($notification->type === 'success')
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        @elseif($notification->type === 'warning')
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        @elseif($notification->type === 'error')
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        @else
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">{{ $notification->title }}</h2>
                            <p class="text-sm text-gray-500">{{ formatTanggalWaktu($notification->created_at) }}</p>
                        </div>
                    </div>
                    @if($notification->is_read)
                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Sudah Dibaca</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Belum Dibaca</span>
                    @endif
                </div>
            </div>

            <!-- Content -->
            <div class="px-6 py-6">
                <div class="prose max-w-none">
                    <p>{{ $notification->message }}</p>
                </div>

                @if($notification->link)
                    <div class="mt-6">
                        <a href="{{ $notification->link }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Lihat Detail
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                @endif

                @if($notification->notifiable_type)
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Terkait dengan:</span>
                            {{ $notification->notifiable_type }} #{{ $notification->notifiable_id }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <p class="text-sm text-gray-500">
                    @if($notification->read_at)
                        Dibaca pada: {{ formatTanggalWaktu($notification->read_at) }}
                    @else
                        Belum dibaca
                    @endif
                </p>
                @if(!$notification->is_read)
                    <form method="POST" action="{{ route('notifications.mark-read', $notification) }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                            Tandai Sudah Dibaca
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
