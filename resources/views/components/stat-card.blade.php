@props(['title', 'value', 'icon' => null, 'color' => 'primary', 'subtitle' => null, 'animate' => false, 'delay' => 0, 'valueId' => null])
<div class="card-lift group relative overflow-hidden rounded-xl bg-dark-900 border border-dark-800 p-6 fade-up" style="animation-delay: {{ $delay }}ms;">
    <div class="absolute inset-x-0 top-0 h-px card-topline"></div>
    <div class="absolute -end-12 -top-12 h-32 w-32 rounded-full bg-{{ $color }}-500/10 blur-2xl group-hover:scale-150 transition-transform duration-500"></div>

    <div class="relative flex items-center justify-between gap-4">
        <div class="min-w-0">
            <p class="text-sm font-medium text-dark-400">{{ $title }}</p>
            <p class="text-3xl font-bold text-gold-gradient mt-1.5 leading-none">
                <span @if($valueId) id="{{ $valueId }}" @endif @if($animate) data-count="{{ $value }}" @endif>{{ $value }}</span>
            </p>
            @if($subtitle)
                <p class="text-xs text-dark-500 mt-2">{{ $subtitle }}</p>
            @endif
        </div>
        @if($icon)
            <div class="h-12 w-12 shrink-0 rounded-xl bg-{{ $color }}-500/10 ring-1 ring-{{ $color }}-500/25 flex items-center justify-center group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                <svg class="h-6 w-6 text-{{ $color }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
            </div>
        @endif
    </div>
</div>