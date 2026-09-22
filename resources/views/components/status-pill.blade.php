{{-- Static status pill (works with compiled CSS — no runtime-built classes). --}}
@props(['status' => 'pending', 'label' => null])

@php
$value = $status instanceof \BackedEnum ? $status->value : (string) $status;
$classes = match ($value) {
    'completed', 'active' => 'bg-emerald-500/10 text-emerald-400',
    'processing', 'pending' => 'bg-amber-500/10 text-amber-400',
    'failed', 'expired' => 'bg-red-500/10 text-red-400',
    default => 'bg-yellow-500/10 text-yellow-400',
};
$text = $label ?? (is_object($status) && method_exists($status, 'label') ? $status->label() : $value);
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium ' . $classes]) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
    {{ $text }}
</span>
