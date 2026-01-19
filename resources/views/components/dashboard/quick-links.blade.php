@props([
    'links' => [],
    'title' => 'Quick Links',
    'variant' => 'default', // default, compact
])

@php
    // Default links if none provided
    if (empty($links)) {
        $links = [
            ['label' => 'Create Pengajuan', 'icon' => 'plus', 'route' => 'pengajuan-dana.create', 'color' => 'blue'],
            ['label' => 'My LPJ', 'icon' => 'document', 'route' => 'lpj.index', 'color' => 'emerald'],
            ['label' => 'My Pengajuan', 'icon' => 'clock', 'route' => 'pengajuan-dana.index', 'color' => 'gray'],
        ];
    }

    // Icon paths
    $icons = [
        'plus' => 'M12 4v16m8-8H4',
        'document' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'clock' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'chart' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'users' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        'check-circle' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'cash' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
        'refresh' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
        'cog' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
        'building' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
        'home' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
    ];

    // Color classes
    $colorClasses = [
        'blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'hover-bg' => 'hover:bg-blue-50', 'border' => 'hover:border-blue-300'],
        'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'hover-bg' => 'hover:bg-emerald-50', 'border' => 'hover:border-emerald-300'],
        'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'hover-bg' => 'hover:bg-amber-50', 'border' => 'hover:border-amber-300'],
        'violet' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-600', 'hover-bg' => 'hover:bg-violet-50', 'border' => 'hover:border-violet-300'],
        'gray' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'hover-bg' => 'hover:bg-gray-50', 'border' => 'hover:border-gray-300'],
        'indigo' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'hover-bg' => 'hover:bg-indigo-50', 'border' => 'hover:border-indigo-300'],
    ];
@endphp

@if ($variant === 'compact')
    <!-- Compact variant -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-900">{{ $title }}</h3>
        </div>
        <div class="p-2">
            @foreach($links as $link)
                @php
                    $colors = $colorClasses[$link['color']] ?? $colorClasses['blue'];
                    $iconPath = $icons[$link['icon']] ?? $icons['home'];
                @endphp
                <a href="{{ route($link['route']) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg {{ $colors['hover-bg'] }} hover:shadow-sm transition-all group">
                    <div class="{{ $colors['bg'] }} rounded-lg p-1.5">
                        <svg class="w-4 h-4 {{ $colors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">{{ $link['label'] }}</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @endforeach
        </div>
    </div>
@else
    <!-- Default variant -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
        </div>
        <div class="p-4">
            <div class="space-y-2">
                @foreach($links as $link)
                    @php
                        $colors = $colorClasses[$link['color']] ?? $colorClasses['blue'];
                        $iconPath = $icons[$link['icon']] ?? $icons['home'];
                    @endphp
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 {{ $colors['border'] }} {{ $colors['hover-bg'] }} transition-all duration-200 group">
                        <div class="flex-shrink-0 {{ $colors['bg'] }} rounded-xl p-3">
                            <svg class="w-5 h-5 {{ $colors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}" />
                            </svg>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-gray-900">{{ $link['label'] }}</span>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif
