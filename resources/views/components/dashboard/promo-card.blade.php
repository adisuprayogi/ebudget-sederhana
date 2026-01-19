@props([
    'badge' => null,
    'title' => 'Promo Title',
    'description' => 'Promo description goes here',
    'cta' => 'Learn More',
    'ctaRoute' => '#',
    'gradient' => 'from-blue-600 to-indigo-600',
    'icon' => null,
])

@php
    $icons = [
        'rocket' => 'M13 10V3L4 14h7v7l9-11h-7z',
        'star' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
        'sparkles' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
        'gift' => 'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7',
        'bullhorn' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z',
    ];

    $iconPath = $icons[$icon] ?? null;

    // Gradient variants with darker hover states
    $gradients = [
        'from-blue-600 to-indigo-600' => 'hover:from-blue-700 hover:to-indigo-700',
        'from-emerald-600 to-teal-600' => 'hover:from-emerald-700 hover:to-teal-700',
        'from-violet-600 to-purple-600' => 'hover:from-violet-700 hover:to-purple-700',
        'from-amber-600 to-orange-600' => 'hover:from-amber-700 hover:to-orange-700',
        'from-rose-600 to-pink-600' => 'hover:from-rose-700 hover:to-pink-700',
    ];

    $hoverGradient = $gradients[$gradient] ?? 'hover:from-blue-700 hover:to-indigo-700';
@endphp

<div class="bg-gradient-to-br {{ $gradient }} {{ $hoverGradient }} rounded-2xl shadow-xl p-6 text-white relative overflow-hidden transition-all duration-300 group hover:scale-[1.02] hover:shadow-2xl">
    <!-- Decorative circles -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>

    <div class="relative z-10">
        <!-- Badge -->
        @if($badge)
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold mb-4 backdrop-blur-sm">
                {{ $badge }}
            </div>
        @endif

        <!-- Icon -->
        @if($iconPath)
            <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4 backdrop-blur-sm">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}" />
                </svg>
            </div>
        @endif

        <!-- Title -->
        <h3 class="text-xl font-bold mb-2">{{ $title }}</h3>

        <!-- Description -->
        <p class="text-white/80 text-sm mb-4 max-w-sm">{{ $description }}</p>

        <!-- CTA Button -->
        @if($cta)
            <a href="{{ $ctaRoute }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-900 rounded-lg font-semibold text-sm hover:bg-white/90 transition-colors group/btn">
                {{ $cta }}
                <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        @endif
    </div>
</div>
