@props([
    'title' => 'Title',
    'value' => 0,
    'subtext' => null,
    'color' => 'blue',
    'type' => 'default', // default, progress, pie, status
    'progress' => null,
    'icon' => null,
    'trend' => null, // up, down, neutral
    'trendValue' => null,
    'link' => null,
])

@php
    // Color configurations
    $colors = [
        'blue' => [
            'bg' => 'bg-blue-500',
            'bg-gradient' => 'from-blue-500 to-blue-600',
            'bg-light' => 'bg-blue-50',
            'text' => 'text-blue-600',
            'text-light' => 'text-blue-100',
            'shadow' => 'shadow-blue-500/30',
            'border' => 'border-blue-200',
        ],
        'emerald' => [
            'bg' => 'bg-emerald-500',
            'bg-gradient' => 'from-emerald-500 to-emerald-600',
            'bg-light' => 'bg-emerald-50',
            'text' => 'text-emerald-600',
            'text-light' => 'text-emerald-100',
            'shadow' => 'shadow-emerald-500/30',
            'border' => 'border-emerald-200',
        ],
        'amber' => [
            'bg' => 'bg-amber-500',
            'bg-gradient' => 'from-amber-500 to-amber-600',
            'bg-light' => 'bg-amber-50',
            'text' => 'text-amber-600',
            'text-light' => 'text-amber-100',
            'shadow' => 'shadow-amber-500/30',
            'border' => 'border-amber-200',
        ],
        'red' => [
            'bg' => 'bg-red-500',
            'bg-gradient' => 'from-red-500 to-red-600',
            'bg-light' => 'bg-red-50',
            'text' => 'text-red-600',
            'text-light' => 'text-red-100',
            'shadow' => 'shadow-red-500/30',
            'border' => 'border-red-200',
        ],
        'violet' => [
            'bg' => 'bg-violet-500',
            'bg-gradient' => 'from-violet-500 to-violet-600',
            'bg-light' => 'bg-violet-50',
            'text' => 'text-violet-600',
            'text-light' => 'text-violet-100',
            'shadow' => 'shadow-violet-500/30',
            'border' => 'border-violet-200',
        ],
        'indigo' => [
            'bg' => 'bg-indigo-500',
            'bg-gradient' => 'from-indigo-500 to-indigo-600',
            'bg-light' => 'bg-indigo-50',
            'text' => 'text-indigo-600',
            'text-light' => 'text-indigo-100',
            'shadow' => 'shadow-indigo-500/30',
            'border' => 'border-indigo-200',
        ],
    ];

    $c = $colors[$color] ?? $colors['blue'];

    // Icon paths
    $icons = [
        'document' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'chart' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'users' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        'money' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'check-circle' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'clock' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'trending-up' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
        'trending-down' => 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6',
        'refresh' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
        'cash' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
        'exclamation' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-1.964-.833-2.694 0L3.34 16c-.77.833-1.964.833-2.694 0L3.34 7c-.77.833.192-2.694 1.732-2.5L12.998 3c.77-.833 1.964-.833 2.694 0l6.938 6.938c.77.833 1.964.833 2.694 0l1.732-2.5c.77-.833.192-2.694-1.732-2.5z',
        'building' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
    ];

    $iconPath = $icons[$icon] ?? null;

    // Card wrapper
    $cardTag = $link ? 'a' : 'div';
    $cardHref = $link ? 'href="' . $link . '"' : '';
@endphp

<{{ $cardTag }} {{ $cardHref }} class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 p-5 border border-gray-100 group {{ $link ? 'hover:scale-[1.02] cursor-pointer' : '' }}">
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $value }}</p>
        </div>

        @if($iconPath)
            <div class="flex-shrink-0 bg-gradient-to-br {{ $c['bg-gradient'] }} rounded-xl p-3 shadow-lg {{ $c['shadow'] }} group-hover:scale-110 transition-transform duration-300">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}" />
                </svg>
            </div>
        @endif
    </div>

    @if($type === 'progress' && $progress !== null)
        <!-- Progress Bar Type -->
        <div class="flex items-center gap-2">
            <div class="flex-1 {{ $c['bg-light'] }} rounded-full h-2">
                <div class="bg-gradient-to-r {{ $c['bg-gradient'] }} h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
            </div>
            <span class="text-xs text-gray-500 font-medium">{{ $progress }}%</span>
        </div>
    @elseif($type === 'pie' && $progress !== null)
        <!-- Pie Chart Indicator -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="relative w-10 h-10">
                    <svg class="w-10 h-10 transform -rotate-90" viewBox="0 0 36 36">
                        <path class="text-gray-200" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3"/>
                        <path class="{{ $c['text'] }}" stroke-dasharray="{{ $progress }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3"/>
                    </svg>
                </div>
                <span class="text-sm text-gray-600">{{ $progress }}%</span>
            </div>
            @if($subtext)
                <span class="text-xs text-gray-500">{{ $subtext }}</span>
            @endif
        </div>
    @elseif($type === 'status')
        <!-- Status Dot Type -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $c['bg'] }} opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 {{ $c['bg'] }}"></span>
                </span>
                <span class="text-xs {{ $c['text'] }} font-medium">Active</span>
            </div>
            @if($trend && $trendValue)
                @php
                    $trendIcon = $trend === 'up' ? 'trending-up' : ($trend === 'down' ? 'trending-down' : 'minus');
                    $trendColor = $trend === 'up' ? 'text-emerald-600' : ($trend === 'down' ? 'text-red-600' : 'text-gray-600');
                @endphp
                <div class="flex items-center gap-1 {{ $trendColor }}">
                    @if($trendIcon !== 'minus')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons[$trendIcon] }}" />
                        </svg>
                    @endif
                    <span class="text-xs font-medium">{{ $trendValue }}</span>
                </div>
            @endif
        </div>
    @else
        <!-- Default Type with Subtext -->
        @if($subtext || $trend)
            <div class="flex items-center justify-between">
                @if($subtext)
                    <p class="text-sm text-gray-500">{{ $subtext }}</p>
                @endif
                @if($trend && $trendValue)
                    @php
                        $trendIcon = $trend === 'up' ? 'trending-up' : ($trend === 'down' ? 'trending-down' : null);
                        $trendColor = $trend === 'up' ? 'text-emerald-600' : ($trend === 'down' ? 'text-red-600' : 'text-gray-600');
                    @endphp
                    <div class="flex items-center gap-1 {{ $trendColor }}">
                        @if($trendIcon)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons[$trendIcon] }}" />
                            </svg>
                        @endif
                        <span class="text-xs font-medium">{{ $trendValue }}</span>
                    </div>
                @endif
            </div>
        @endif
    @endif
</{{ $cardTag }}>
