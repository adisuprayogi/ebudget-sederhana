@if(!isset($color))
    $color = 'blue'
@endif

@if(!isset($label))
    $label = 'Label'
@endif

@if(!isset($value))
    $value = 0
@endif

@if(!isset($icon))
    $icon = 'document'
@endif

<div class="bg-white border border-slate-200 rounded-xl p-5">
    <div class="flex items-center justify-between mb-3">
        <p class="text-slate-600 text-sm font-medium">{{ $label }}</p>

        <?php
            $bgClass = 'bg-slate-100';
            $textClass = 'text-slate-600';

            if($color === 'blue') {
                $bgClass = 'bg-blue-50';
                $textClass = 'text-blue-600';
            } elseif($color === 'emerald') {
                $bgClass = 'bg-emerald-50';
                $textClass = 'text-emerald-600';
            } elseif($color === 'red') {
                $bgClass = 'bg-red-50';
                $textClass = 'text-red-600';
            } elseif($color === 'amber') {
                $bgClass = 'bg-amber-50';
                $textClass = 'text-amber-600';
            } elseif($color === 'violet') {
                $bgClass = 'bg-violet-50';
                $textClass = 'text-violet-600';
            }
        ?>

        <div class="w-10 h-10 {{ $bgClass }} rounded-lg flex items-center justify-center">
            <?php
                $iconPath = '';
                if($icon === 'user') {
                    $iconPath = 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z';
                } elseif($icon === 'document') {
                    $iconPath = 'M9 12l2 2 4-4m7-2h-3m2 2l-2-2m2 2v6a1 1 0 01-1 1h3m0 0a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 002 2h2a1 1 0 002 2m0 0a1 1 0 002 2m-6 0a1 1 0 01-1h2a1 1 0 01-1v-1m0 0h-6a9 9 0 01-18 0 9 9 0 0118 0z';
                } elseif($icon === 'money') {
                    $iconPath = 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                } elseif($icon === 'check-circle') {
                    $iconPath = 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z';
                } elseif($icon === 'chart') {
                    $iconPath = 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002 2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z';
                } else {
                    $iconPath = 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2v12a2 2 0 002 2h2a2 2 0 002 2v6a2 2 0 002 2z';
                }
            ?>
            <svg class="w-5 h-5 {{ $textClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}" />
            </svg>
        </div>

        <p class="text-2xl font-bold text-slate-800">{{ $value }}</p>
    </div>
</div>
