@extends('layouts.app', ['title' => 'Inbox - ' . $purchase->phoneNumber?->phone_number])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('my-numbers.show', $purchase) }}" class="inline-flex items-center gap-1 text-sm text-dark-400 hover:text-white mb-6">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Number
    </a>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Inbox</h1>
            <p class="text-dark-400 font-mono">{{ $purchase->phoneNumber?->phone_number }}</p>
        </div>
    </div>

    @if($messages->count())
        <div class="space-y-3">
            @foreach($messages as $message)
            <a href="{{ route('inbox.show', ['purchase' => $purchase, 'message' => $message]) }}"
               class="block rounded-xl bg-dark-900 border border-dark-800 p-4 hover:border-dark-700 transition-colors {{ !$message->is_read ? 'border-l-2 border-l-primary-500' : '' }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="text-sm font-semibold text-white">{{ $message->sender ?? 'Unknown' }}</p>
                            @if(!$message->is_read)
                                <span class="h-2 w-2 rounded-full bg-primary-500"></span>
                            @endif
                        </div>
                        <p class="text-sm text-dark-300 line-clamp-2">{{ Str::limit($message->message, 200) }}</p>
                    </div>
                    <span class="text-xs text-dark-500 shrink-0">{{ $message->received_at?->diffForHumans() }}</span>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-6">{{ $messages->links() }}</div>
    @else
        <x-empty-state title="No messages yet" description="Messages received on this number will appear here." />
    @endif
</div>
@endsection
