@extends('layouts.app', ['title' => 'Number Details - IQAB'])

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('my-numbers.index') }}" class="inline-flex items-center gap-1 text-sm text-dark-400 hover:text-white mb-6">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to My Numbers
    </a>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6 mb-6">
        <div class="text-center mb-6">
            <p class="text-3xl font-mono font-bold text-white">{{ $purchase->phoneNumber?->phone_number }}</p>
            <p class="text-dark-400 mt-1">{{ $purchase->phoneNumber?->country }}</p>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                bg-{{ $purchase->status->value === 'active' ? 'emerald' : 'red' }}-500/10
                text-{{ $purchase->status->value === 'active' ? 'emerald' : 'red' }}-400 mt-3">
                {{ $purchase->status->label() }}
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <div class="rounded-lg bg-dark-800 p-3 text-center">
                <p class="text-xs text-dark-500">Price</p>
                <p class="text-sm font-semibold text-white">{{ number_format($purchase->price, 2) }} {{ currency() }}</p>
            </div>
            <div class="rounded-lg bg-dark-800 p-3 text-center">
                <p class="text-xs text-dark-500">Purchased</p>
                <p class="text-sm font-semibold text-white">{{ $purchase->purchased_at?->format('M d') ?? 'N/A' }}</p>
            </div>
            <div class="rounded-lg bg-dark-800 p-3 text-center">
                <p class="text-xs text-dark-500">Expires</p>
                <p class="text-sm font-semibold text-white">{{ $purchase->expires_at?->format('M d') ?? 'N/A' }}</p>
            </div>
            <div class="rounded-lg bg-dark-800 p-3 text-center">
                <p class="text-xs text-dark-500">Messages</p>
                <p class="text-sm font-semibold text-white">{{ $messageCount }}</p>
            </div>
        </div>

        @if($purchase->provider_reference)
            <div class="rounded-lg bg-dark-800 p-4 mb-6">
                <p class="text-xs text-dark-500 mb-1">Provider Reference</p>
                <p class="text-sm font-mono text-dark-300">{{ $purchase->provider_reference }}</p>
            </div>
        @endif

        @if($purchase->status->value === 'active')
            <a href="{{ route('inbox.index', $purchase) }}" class="block text-center rounded-lg bg-primary-600 px-4 py-3 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">
                Open Inbox
            </a>
        @endif
    </div>
</div>
@endsection
