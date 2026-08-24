@extends('layouts.app', ['title' => 'Number Details - IQAB'])

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('numbers.index') }}" class="inline-flex items-center gap-1 text-sm text-dark-400 hover:text-white mb-6">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Marketplace
    </a>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
        <div class="text-center mb-6">
            <p class="text-3xl font-mono font-bold text-white">{{ $number->phone_number }}</p>
            <p class="text-dark-400 mt-1">{{ $number->country }} ({{ $number->country_code }})</p>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $number->status->color() }}-500/10 text-{{ $number->status->color() }}-400 mt-3">{{ $number->status->label() }}</span>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="rounded-lg bg-dark-800 p-4 text-center">
                <p class="text-xs text-dark-500">Price</p>
                <p class="text-xl font-bold text-white">{{ number_format($number->price, 2) }} SAR</p>
            </div>
            <div class="rounded-lg bg-dark-800 p-4 text-center">
                <p class="text-xs text-dark-500">Country</p>
                <p class="text-xl font-bold text-white">{{ $number->country_code }}</p>
            </div>
        </div>

        @if($number->expires_at)
            <p class="text-sm text-dark-400 text-center mb-6">Expires: {{ $number->expires_at->format('M d, Y H:i') }}</p>
        @endif

        @if($number->status->value === 'available')
        <form method="POST" action="{{ route('numbers.purchase', $number) }}" class="text-center">
            @csrf
            <input type="hidden" name="phone_number_id" value="{{ $number->id }}">
            <button type="submit" onclick="return confirm('Buy this number for {{ number_format($number->price, 2) }} SAR?')" class="rounded-lg bg-primary-600 px-8 py-3 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">
                Purchase for {{ number_format($number->price, 2) }} SAR
            </button>
        </form>
        @else
        <p class="text-sm text-dark-400">This number is no longer available for purchase.</p>
        @endif
    </div>
</div>
@endsection
