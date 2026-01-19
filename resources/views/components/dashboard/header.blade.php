@props([
    'userName' => auth()->user()->name ?? null,
    'showDate' => true,
    'showLiveIndicator' => true,
    'compact' => false
])

@php
    $hour = now()->hour;
    if ($hour >= 5 && $hour < 12) {
        $greeting = 'Good morning';
    } elseif ($hour >= 12 && $hour < 17) {
        $greeting = 'Good afternoon';
    } elseif ($hour >= 17 && $hour < 21) {
        $greeting = 'Good evening';
    } else {
        $greeting = 'Good night';
    }
@endphp

@if ($compact)
    <!-- Compact Header -->
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500">{{ $greeting }},</p>
            <h1 class="text-xl font-bold text-gray-900">{{ $userName }}</h1>
        </div>
        @if($showDate || $showLiveIndicator)
            <div class="flex items-center gap-3">
                @if($showDate)
                    <div class="hidden sm:flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-lg">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm text-gray-600">{{ now()->translatedFormat('l, d F Y') }}</span>
                    </div>
                @endif
                @if($showLiveIndicator)
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        <span class="text-xs text-gray-500 hidden sm:inline">Live</span>
                    </div>
                @endif
            </div>
        @endif
    </div>
@else
    <!-- Full Header -->
    <div class="mb-6">
        <p class="text-gray-500 text-sm mb-1">{{ $greeting }},</p>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $userName }}</h1>
        @if($showDate)
            <p class="text-gray-500 text-sm mt-2">{{ now()->translatedFormat('l, d F Y') }}</p>
        @endif
    </div>
@endif
