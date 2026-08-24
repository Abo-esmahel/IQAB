@extends('layouts.app', ['title' => 'Marketplace - IQAB'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-white">Number Marketplace</h1>
    </div>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-4 mb-6">
        <form method="GET" action="{{ route('numbers.index') }}" class="flex flex-col sm:flex-row gap-3">
            <select name="country" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                <option value="">All Countries</option>
                @foreach($countries as $country)
                    <option value="{{ $country }}" {{ request('country') === $country ? 'selected' : '' }}>{{ $country }}</option>
                @endforeach
            </select>
            <input type="number" name="min_price" placeholder="Min Price" value="{{ request('min_price') }}" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none w-full sm:w-32">
            <input type="number" name="max_price" placeholder="Max Price" value="{{ request('max_price') }}" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none w-full sm:w-32">
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">Filter</button>
            @if(request()->hasAny(['country', 'min_price', 'max_price']))
                <a href="{{ route('numbers.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 px-4 py-2 text-sm font-medium text-dark-300 hover:text-white transition-colors">Clear</a>
            @endif
        </form>
    </div>

    @if($numbers->count())
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($numbers as $number)
            <div class="rounded-xl bg-dark-900 border border-dark-800 p-5 hover:border-dark-700 transition-colors">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <p class="text-lg font-mono font-semibold text-white">{{ $number->phone_number }}</p>
                        <p class="text-sm text-dark-400">{{ $number->country }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $number->status->color() }}-500/10 text-{{ $number->status->color() }}-400">{{ $number->status->label() }}</span>
                </div>
                <div class="flex items-end justify-between mt-4">
                    <div>
                        <p class="text-xs text-dark-500">Price</p>
                        <p class="text-xl font-bold text-white">{{ number_format($number->price, 2) }} <span class="text-sm text-dark-400">SAR</span></p>
                    </div>
                    <a href="{{ config('app.telegram_contact') }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9.78 18.65l.28-4.23 7.68-6.92c.34-.31-.07-.46-.52-.19L7.74 13.3 3.64 12c-.88-.25-.89-.86.2-1.3l15.97-6.16c.73-.33 1.43.18 1.15 1.3l-2.72 12.81c-.19.91-.74 1.13-1.5.71L12.6 16.3l-1.99 1.93c-.23.23-.42.42-.83.42z"/></svg>
                        Buy via Telegram
                    </a>
                </div>
                @if($number->expires_at)
                    <p class="text-xs text-dark-500 mt-3">Expires: {{ $number->expires_at->format('M d, Y') }}</p>
                @endif
            </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $numbers->withQueryString()->links() }}</div>
    @else
        <x-empty-state title="No numbers available" description="Check back later for new numbers." actionLabel="Back to Dashboard" actionUrl="{{ route('dashboard') }}" />
    @endif
</div>
@endsection
