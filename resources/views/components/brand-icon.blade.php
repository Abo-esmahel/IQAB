{{-- Contact channel visual: custom upload > real brand PNG > generic icon. --}}
@props(['type' => 'other', 'image' => null, 'class' => 'h-16 w-16'])

@php
$slug = ['twitter' => 'x'][$type] ?? $type;
$brands = ['whatsapp', 'telegram', 'x', 'instagram', 'facebook', 'messenger', 'discord', 'tiktok', 'youtube', 'snapchat', 'signal', 'reddit', 'twitch', 'viber', 'pinterest', 'line'];
$needsLight = in_array($slug, ['x', 'tiktok'], true);
@endphp

@if($image)
    <img src="{{ $image }}" alt="{{ $type }}" loading="lazy" decoding="async" class="{{ $class }} rounded-2xl object-cover">
@elseif(in_array($slug, $brands, true))
    <img src="{{ asset('images/brands/' . $slug . '.png') }}" alt="{{ $type }}" loading="lazy" decoding="async" class="{{ $class }} {{ $needsLight ? 'brightness-0 invert' : '' }}">
@else
    <x-contact-icon :type="$type" :class="$class" />
@endif
