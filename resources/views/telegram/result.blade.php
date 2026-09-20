@extends('layouts.app', ['title' => 'Service Result - IQAB'])

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('telegram.index') }}" class="inline-flex items-center gap-1 text-sm text-dark-400 hover:text-white mb-6">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Services
    </a>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4 pb-4 border-b border-dark-800">
            <div>
                <h1 class="text-lg font-semibold text-white">{{ $request->telegramService?->name }}</h1>
                <p class="text-sm text-dark-400">Target: {{ $request->target_identifier }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                bg-{{ match($request->status->value) { 'completed' => 'emerald', 'processing' => 'amber', 'failed' => 'red', default => 'yellow' } }}-500/10
                text-{{ match($request->status->value) { 'completed' => 'emerald', 'processing' => 'amber', 'failed' => 'red', default => 'yellow' } }}-400">
                {{ $request->status->label() }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="rounded-lg bg-dark-800 p-3 text-center">
                <p class="text-xs text-dark-500">Price Paid</p>
                <p class="text-sm font-semibold text-white">{{ number_format($request->price, 2) }} {{ currency() }}</p>
            </div>
            <div class="rounded-lg bg-dark-800 p-3 text-center">
                <p class="text-xs text-dark-500">Date</p>
                <p class="text-sm font-semibold text-white">{{ $request->created_at->format('M d, Y H:i') }}</p>
            </div>
        </div>

        @if($request->result)
            <div class="mt-4">
                <h3 class="text-sm font-medium text-dark-300 mb-2">Result</h3>
                <pre class="text-xs text-dark-400 bg-dark-800 rounded-lg p-4 overflow-x-auto">{{ json_encode($request->result, JSON_PRETTY_PRINT) }}</pre>
            </div>
        @endif

        @if($request->error_message)
            <div class="mt-4 rounded-lg bg-red-500/10 border border-red-500/20 p-4">
                <p class="text-sm text-red-400">{{ $request->error_message }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
