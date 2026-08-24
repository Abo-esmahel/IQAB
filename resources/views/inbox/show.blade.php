@extends('layouts.app', ['title' => 'Message - IQAB'])

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('inbox.index', $purchase) }}" class="inline-flex items-center gap-1 text-sm text-dark-400 hover:text-white mb-6">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Inbox
    </a>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
        <div class="flex items-center justify-between mb-4 pb-4 border-b border-dark-800">
            <div>
                <p class="text-lg font-semibold text-white">{{ $message->sender ?? 'Unknown Sender' }}</p>
                <p class="text-sm text-dark-400 font-mono">{{ $purchase->phoneNumber?->phone_number }}</p>
            </div>
            <span class="text-sm text-dark-500">{{ $message->received_at?->format('M d, Y H:i') }}</span>
        </div>

        <div class="prose prose-invert max-w-none">
            <p class="text-dark-200 whitespace-pre-wrap leading-relaxed">{{ $message->message }}</p>
        </div>

        @if($message->metadata)
        <div class="mt-6 pt-4 border-t border-dark-800">
            <p class="text-xs text-dark-500 mb-2">Metadata</p>
            <pre class="text-xs text-dark-400 bg-dark-800 rounded-lg p-3 overflow-x-auto">{{ json_encode($message->metadata, JSON_PRETTY_PRINT) }}</pre>
        </div>
        @endif
    </div>
</div>
@endsection
