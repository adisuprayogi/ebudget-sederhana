@props(['theme' => 'blue'])

@php
    $themes = [
        'blue' => [
            'primary' => 'blue',
            'secondary' => 'indigo',
            'text' => 'text-blue-700',
            'border' => 'border-blue-200',
            'hover' => 'hover:bg-blue-50',
            'dots' => 'text-blue-600',
        ],
        'emerald' => [
            'primary' => 'emerald',
            'secondary' => 'green',
            'text' => 'text-emerald-700',
            'border' => 'border-emerald-200',
            'hover' => 'hover:bg-emerald-50',
            'dots' => 'text-emerald-600',
        ],
        'amber' => [
            'primary' => 'amber',
            'secondary' => 'orange',
            'text' => 'text-amber-700',
            'border' => 'border-amber-200',
            'hover' => 'hover:bg-amber-50',
            'dots' => 'text-amber-600',
        ],
        'purple' => [
            'primary' => 'purple',
            'secondary' => 'violet',
            'text' => 'text-purple-700',
            'border' => 'border-purple-200',
            'hover' => 'hover:bg-purple-50',
            'dots' => 'text-purple-600',
        ],
        'red' => [
            'primary' => 'red',
            'secondary' => 'rose',
            'text' => 'text-red-700',
            'border' => 'border-red-200',
            'hover' => 'hover:bg-red-50',
            'dots' => 'text-red-600',
        ],
    ];

    $t = $themes[$theme] ?? $themes['blue'];
    $p = $t['primary'];
    $s = $t['secondary'];
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center gap-1">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="relative inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-400 bg-gray-50 rounded-lg cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-3 py-1.5 text-xs font-medium {{ $t['text'] }} bg-white border {{ $t['border'] }} rounded-lg {{ $t['hover'] }} transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="relative inline-flex items-center px-2 py-1.5 text-xs font-medium {{ $t['dots'] }}">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="relative inline-flex items-center px-3 py-1.5 text-xs font-bold text-white bg-gradient-to-r from-{{ $p }}-600 to-{{ $s }}-600 rounded-lg shadow-lg shadow-{{ $p }}-500/30">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="relative inline-flex items-center px-3 py-1.5 text-xs font-medium {{ $t['text'] }} bg-white border {{ $t['border'] }} rounded-lg {{ $t['hover'] }} transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-3 py-1.5 text-xs font-medium {{ $t['text'] }} bg-white border {{ $t['border'] }} rounded-lg {{ $t['hover'] }} transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        @else
            <span class="relative inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-400 bg-gray-50 rounded-lg cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </span>
        @endif
    </nav>
@endif
