@props(['title', 'value', 'icon' => null, 'color' => 'primary', 'subtitle' => null])
<div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-dark-400">{{ $title }}</p>
            <p class="text-2xl font-bold text-white mt-1">{{ $value }}</p>
            @if($subtitle)
                <p class="text-xs text-dark-500 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
        @if($icon)
            <div class="h-12 w-12 rounded-xl bg-{{ $color }}-500/10 flex items-center justify-center">
                <svg class="h-6 w-6 text-{{ $color }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
            </div>
        @endif
    </div>
</div>