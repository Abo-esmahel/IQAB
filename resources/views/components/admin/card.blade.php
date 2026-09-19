@props(['padding' => 'p-6 sm:p-8'])
<div {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-2xl border border-dark-800 bg-dark-900/70 backdrop-blur-sm shadow-xl shadow-black/20']) }}>
    <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary-500/60 to-transparent"></div>
    <div class="{{ $padding }}">
        {{ $slot }}
    </div>
</div>