@props([
    'name' => null,
    'label' => null,
    'type' => 'text',
    'value' => null,
    'hint' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'step' => null,
    'min' => null,
    'max' => null,
    'maxlength' => null,
    'autocomplete' => null,
    'id' => null,
    'wrapperClass' => null,
])
@php
    $id = $id ?? $name;
    $hasError = $errors->has($name);
    $oldValue = old($name);
    $currentValue = $oldValue !== null ? $oldValue : $value;
@endphp
<div class="{{ $wrapperClass }}">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-dark-300 mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-primary-400" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        @if($currentValue !== null && $type !== 'password') value="{{ $currentValue }}" @endif
        @if($placeholder !== null) placeholder="{{ $placeholder }}" @endif
        @if($step !== null) step="{{ $step }}" @endif
        @if($min !== null) min="{{ $min }}" @endif
        @if($max !== null) max="{{ $max }}" @endif
        @if($maxlength !== null) maxlength="{{ $maxlength }}" @endif
        @if($autocomplete !== null) autocomplete="{{ $autocomplete }}" @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        @if($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
        class="w-full rounded-lg bg-dark-800 border px-4 py-2.5 text-sm text-white placeholder-dark-500 outline-none transition-colors {{ $hasError ? 'border-red-500/60 focus:border-red-500 focus:ring-1 focus:ring-red-500/40' : 'border-dark-700 focus:border-primary-500 focus:ring-1 focus:ring-primary-500/40' }} {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}"
    >

    @if($hasError)
        <p id="{{ $id }}-error" class="mt-1.5 text-xs text-red-400">{{ $errors->first($name) }}</p>
    @elseif($hint)
        <p class="mt-1.5 text-xs text-dark-500">{{ $hint }}</p>
    @endif
</div>