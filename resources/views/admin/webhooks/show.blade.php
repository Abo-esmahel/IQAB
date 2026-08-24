@extends('layouts.app', ['title' => 'Webhook Detail - IQAB'])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.webhooks.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-2 text-dark-400 hover:text-white transition-colors">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Webhook Log #{{ $webhook->id }}</h1>
            <p class="text-sm text-dark-400">{{ $webhook->created_at->format('M d, Y H:i:s') }}</p>
        </div>
    </div>

    <div class="grid sm:grid-cols-3 gap-4 mb-8">
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <p class="text-sm text-dark-400">Provider</p>
            <p class="text-lg font-bold text-white mt-1">{{ ucfirst($webhook->provider ?? 'N/A') }}</p>
        </div>
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <p class="text-sm text-dark-400">Event</p>
            <p class="text-lg font-bold text-white mt-1 font-mono text-sm">{{ $webhook->event ?? 'N/A' }}</p>
        </div>
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <p class="text-sm text-dark-400">Status</p>
            @php
                $statusColor = match($webhook->status->value) {
                    'received' => 'yellow',
                    'processed' => 'emerald',
                    'failed' => 'red',
                    'ignored' => 'gray',
                    default => 'gray',
                };
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1 bg-{{ $statusColor }}-500/10 text-{{ $statusColor }}-400">
                {{ $webhook->status->label() }}
            </span>
        </div>
    </div>

    @if($webhook->error_message)
        <div class="rounded-xl bg-dark-900 border border-red-500/20 p-6 mb-6">
            <h2 class="text-lg font-semibold text-red-400 mb-2">Error</h2>
            <p class="text-sm text-dark-300 font-mono">{{ $webhook->error_message }}</p>
        </div>
    @endif

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6 mb-6">
        <h2 class="text-lg font-semibold text-white mb-4">Payload</h2>
        <div class="rounded-lg bg-dark-800 border border-dark-700 p-4 overflow-x-auto">
            <pre class="text-sm text-dark-200 font-mono whitespace-pre-wrap">{{ is_array($webhook->payload) ? json_encode($webhook->payload, JSON_PRETTY_PRINT) : (is_string($webhook->payload) && $webhook->payload ? json_encode(json_decode($webhook->payload), JSON_PRETTY_PRINT) : 'No payload data') }}</pre>
        </div>
    </div>

    @if($webhook->headers)
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Headers</h2>
            <div class="rounded-lg bg-dark-800 border border-dark-700 p-4 overflow-x-auto">
                <pre class="text-sm text-dark-200 font-mono whitespace-pre-wrap">{{ is_string($webhook->headers) ? $webhook->headers : json_encode($webhook->headers, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
    @endif
</div>
@endsection
