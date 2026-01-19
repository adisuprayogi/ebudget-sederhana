@props([
    'title' => 'Status Panel',
    'items' => [],
    'icon' => 'clock',
    'emptyMessage' => 'No items to display',
    'emptyIcon' => 'check-circle',
    'showTime' => false,
])

@php
    $icons = [
        'clock' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'calendar' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        'user' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
        'users' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        'check-circle' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'exclamation-circle' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'information-circle' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'bell' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
        'coffee' => 'M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z',
    ];

    $iconPath = $icons[$icon] ?? $icons['clock'];
    $emptyIconPath = $icons[$emptyIcon] ?? $icons['check-circle'];
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                @if(count($items) > 0)
                    <p class="text-xs text-gray-500 mt-0.5">{{ count($items) }} item{{ count($items) > 1 ? 's' : '' }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="p-4">
        @if(count($items) > 0)
            <div class="space-y-2">
                @foreach($items as $item)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                        <!-- Avatar or Icon -->
                        @if(isset($item['avatar']) || isset($item['initials']))
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                                @if(isset($item['avatar']))
                                    <img src="{{ $item['avatar'] }}" alt="" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <span class="text-white text-sm font-semibold">{{ $item['initials'] ?? '?' }}</span>
                                @endif
                            </div>
                        @else
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        @endif

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            @if(isset($item['name']))
                                <p class="font-medium text-gray-900 truncate">{{ $item['name'] }}</p>
                            @endif
                            @if(isset($item['label']))
                                <p class="text-sm text-gray-500 truncate">{{ $item['label'] }}</p>
                            @endif
                            @if($showTime && isset($item['time']))
                                <p class="text-xs text-gray-400 mt-0.5">{{ $item['time'] }}</p>
                            @endif
                        </div>

                        <!-- Badge -->
                        @if(isset($item['badge']))
                            <span class="flex-shrink-0 inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $item['badgeColor'] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $item['badge'] }}
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-50 rounded-full mb-4">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $emptyIconPath }}" />
                    </svg>
                </div>
                <p class="text-gray-500 font-medium">{{ $emptyMessage }}</p>
            </div>
        @endif
    </div>

    <!-- Footer (optional) -->
    @if(isset($footerLink) && isset($footerLabel))
        <div class="px-4 py-3 border-t border-gray-100">
            <a href="{{ $footerLink }}" class="flex items-center justify-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                {{ $footerLabel }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    @endif
</div>
