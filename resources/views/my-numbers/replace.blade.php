@extends('layouts.app', ['title' => 'Replace Number - IQAB'])

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('my-numbers.show', $purchase) }}" class="inline-flex items-center gap-1 text-sm text-dark-400 hover:text-white mb-6">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Number
    </a>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6 mb-6 text-center">
        <p class="text-xs uppercase tracking-wide text-dark-500">Replacing</p>
        <p class="text-2xl font-mono font-bold text-white mt-1">{{ $purchase->phoneNumber?->phone_number }}</p>
        <p class="text-sm text-dark-400 mt-1">{{ $purchase->phoneNumber?->country }}</p>
    </div>

    @if($alternatives->count())
    <form method="POST" action="{{ route('my-numbers.replace.store', $purchase) }}">
        @csrf
        <div class="grid sm:grid-cols-2 gap-3 mb-6">
            @foreach($alternatives as $number)
            <label class="cursor-pointer rounded-xl bg-dark-900 border border-dark-800 p-4 transition-all hover:border-primary-500/50 has-checked:border-primary-500 has-checked:ring-1 has-checked:ring-primary-500/40">
                <div class="flex items-center gap-3">
                    <input type="radio" name="phone_number_id" value="{{ $number->id }}" required class="h-4 w-4 accent-amber-500">
                    <div class="min-w-0">
                        <p class="font-mono font-semibold text-white truncate">{{ $number->phone_number }}</p>
                        <p class="text-xs text-dark-400">{{ $number->country }} · {{ number_format($number->price, 2) }} {{ currency() }}</p>
                    </div>
                </div>
            </label>
            @endforeach
        </div>
        @error('phone_number_id')
            <p class="text-sm text-red-400 mb-4">{{ $message }}</p>
        @enderror
        <button type="submit" onclick="return confirm('Swap to the selected number? Your current number will be released.');"
                class="w-full rounded-lg bg-primary-600 px-4 py-3 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">
            Confirm Replacement
        </button>
        <p class="mt-3 text-center text-xs text-dark-500">Any price difference will be settled with support on Telegram.</p>
    </form>
    @else
        <x-empty-state title="No alternatives available" description="There are no other available numbers right now. Please check back later or contact support." actionLabel="Contact Support" actionUrl="{{ route('contact') }}" />
    @endif
</div>
@endsection
