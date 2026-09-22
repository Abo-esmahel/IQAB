@extends('layouts.app', ['title' => 'Number Details - IQAB'])

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('my-numbers.index') }}" class="inline-flex items-center gap-1 text-sm text-dark-400 hover:text-white mb-6">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to My Numbers
    </a>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6 mb-6">
        <div class="text-center mb-6">
            <p class="text-2xl sm:text-3xl font-mono font-bold text-white break-all">{{ $purchase->phoneNumber?->phone_number }}</p>
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

    @if(in_array($purchase->status->value, ['active', 'pending'], true))
    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6 mb-6">
        <h2 class="text-lg font-semibold text-white mb-4">Manage Number</h2>

        {{-- Nickname --}}
        <form method="POST" action="{{ route('my-numbers.update', $purchase) }}" class="mb-5">
            @csrf @method('PUT')
            <label class="block text-sm font-medium text-dark-300 mb-1.5">Nickname (optional)</label>
            <div class="flex gap-2">
                <input type="text" name="label" value="{{ old('label', $purchase->label) }}" maxlength="50" placeholder="e.g. Business line"
                       class="flex-1 rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                <button type="submit" class="rounded-lg bg-dark-800 border border-dark-700 px-4 py-2 text-sm font-medium text-dark-200 hover:text-white hover:border-primary-500/40 transition-colors">Save</button>
            </div>
        </form>

        <div class="grid sm:grid-cols-2 gap-3">
            @if($purchase->status->value === 'active')
                <a href="{{ route('my-numbers.replace', $purchase) }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm font-medium text-dark-200 hover:text-white hover:border-primary-500/40 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Replace Number
                </a>
            @endif
            <form method="POST" action="{{ route('my-numbers.release', $purchase) }}" onsubmit="return confirm('Release this number? You will lose access to it and its messages.');">
                @csrf
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-red-500/10 border border-red-500/20 px-4 py-2.5 text-sm font-medium text-red-400 hover:bg-red-500/20 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Release Number
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
