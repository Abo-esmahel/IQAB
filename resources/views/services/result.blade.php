@extends('layouts.app', ['title' => 'Service Result - IQAB'])

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('services.index') }}" class="inline-flex items-center gap-1 text-sm text-dark-400 hover:text-white mb-6">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Services
    </a>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
        <div class="flex items-center justify-between mb-4 pb-4 border-b border-dark-800">
            <div>
                <h1 class="text-lg font-semibold text-white">{{ $purchase->marketService?->name }}</h1>
                <p class="text-sm text-dark-400">{{ $purchase->created_at->format('M d, Y H:i') }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                bg-{{ $purchase->status === 'completed' ? 'emerald' : ($purchase->status === 'failed' ? 'red' : 'yellow') }}-500/10
                text-{{ $purchase->status === 'completed' ? 'emerald' : ($purchase->status === 'failed' ? 'red' : 'yellow') }}-400">
                {{ ucfirst($purchase->status) }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="rounded-lg bg-dark-800 p-3 text-center">
                <p class="text-xs text-dark-500">Price Paid</p>
                <p class="text-sm font-semibold text-white">{{ number_format($purchase->price, 2) }} SAR</p>
            </div>
            <div class="rounded-lg bg-dark-800 p-3 text-center">
                <p class="text-xs text-dark-500">Balance After</p>
                <p class="text-sm font-semibold text-white">{{ number_format($purchase->balance_after ?? 0, 2) }} SAR</p>
            </div>
        </div>

        @if($purchase->input_data)
            <div class="mb-4">
                <p class="text-xs text-dark-500 mb-1">Your Input</p>
                <p class="text-sm text-dark-200 bg-dark-800 rounded-lg p-3">{{ $purchase->input_data }}</p>
            </div>
        @endif

        @if($purchase->result)
            <div class="mb-4">
                <p class="text-xs text-dark-500 mb-2">Result</p>
                <pre class="text-xs text-dark-400 bg-dark-800 rounded-lg p-4 overflow-x-auto">{{ json_encode($purchase->result, JSON_PRETTY_PRINT) }}</pre>
            </div>
        @endif

        @if($purchase->error_message)
            <div class="rounded-lg bg-red-500/10 border border-red-500/20 p-4">
                <p class="text-sm text-red-400">{{ $purchase->error_message }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
