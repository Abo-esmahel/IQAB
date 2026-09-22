@extends('layouts.app', ['title' => 'Telegram Tools - IQAB'])

@php
$typeMeta = [
    'account_lookup' => [
        'title' => 'How to use',
        'hint' => 'Enter a username like @durov, a phone like +15551234567, or a numeric ID.',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>',
    ],
    'account_report' => [
        'title' => 'How to use',
        'hint' => 'Enter the exact @username or ID of the account to report.',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.654 48.654 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5"/>',
    ],
    'account_information' => [
        'title' => 'How to use',
        'hint' => 'Enter a username, phone, or ID to pull the full available profile.',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.38 0 2.5-1.12 2.5-2.5S12.38 9 11 9"/>',
    ],
];
@endphp

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Telegram Tools</h1>
            <p class="text-sm text-dark-400 mt-1">Professional lookup, report & intelligence services — results in seconds.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-2 rounded-lg bg-dark-900 border border-dark-800 px-3 py-2 text-xs text-dark-400">
                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                {{ $stats['total'] }} requests · {{ $stats['completed'] }} completed
            </span>
            <a href="{{ route('telegram.history') }}" class="rounded-lg bg-dark-800 border border-dark-700 px-4 py-2 text-sm font-medium text-dark-200 hover:text-white hover:border-primary-500/40 transition-colors">Request History</a>
        </div>
    </div>

    {{-- Steps --}}
    <div class="grid sm:grid-cols-3 gap-3 mb-8">
        @foreach([['n' => '1', 't' => 'Pick a service', 'd' => 'Lookup, report or full information.'], ['n' => '2', 't' => 'Enter the target', 'd' => 'Username, phone number or Telegram ID.'], ['n' => '3', 't' => 'Get the result', 'd' => 'Instant processing with a full report.']] as $step)
        <div class="flex items-center gap-3 rounded-xl bg-dark-900/60 border border-dark-800 px-4 py-3">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-500/15 border border-primary-500/30 text-sm font-bold text-primary-400">{{ $step['n'] }}</span>
            <div>
                <p class="text-sm font-semibold text-white">{{ $step['t'] }}</p>
                <p class="text-xs text-dark-500">{{ $step['d'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    @if($services->count())
    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4 mb-10">
        @foreach($services as $service)
        @php $meta = $typeMeta[$service->type instanceof \BackedEnum ? $service->type->value : $service->type] ?? $typeMeta['account_lookup']; @endphp
        <div class="group rounded-2xl bg-dark-900 border border-dark-800 p-6 card-lift flex flex-col" x-data="{ open: false, sending: false }">
            <div class="flex items-start justify-between mb-4">
                <div class="h-12 w-12 rounded-xl bg-primary-500/10 ring-1 ring-primary-500/25 flex items-center justify-center">
                    <svg class="h-6 w-6 text-primary-400" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">{!! $meta['icon'] !!}</svg>
                </div>
                <span class="rounded-lg bg-primary-500/10 border border-primary-500/20 px-2.5 py-1 text-sm font-bold text-primary-300">{{ number_format($service->price, 2) }} <span class="text-[11px] font-medium">{{ currency() }}</span></span>
            </div>
            <h3 class="text-lg font-semibold text-white">{{ $service->name }}</h3>
            <p class="text-sm text-dark-400 mt-1 flex-1">{{ $service->description }}</p>

            <button @click="open = !open" class="mt-5 w-full rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">
                <span x-show="!open">Use Service</span><span x-show="open" x-cloak>Close</span>
            </button>

            <div x-show="open" x-cloak x-transition.opacity class="mt-4 pt-4 border-t border-dark-800">
                <p class="text-xs text-dark-500 mb-3"><span class="font-semibold text-dark-300">{{ $meta['title'] }}:</span> {{ $meta['hint'] }}</p>
                <form method="POST" action="{{ route('telegram.submit') }}" @submit="sending = true">
                    @csrf
                    <input type="hidden" name="telegram_service_id" value="{{ $service->id }}">
                    <div class="mb-3">
                        <input type="text" name="target_identifier" required minlength="3" maxlength="100" placeholder="@username or +1555..." autocomplete="off"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors font-mono">
                    </div>
                    <button type="submit" :disabled="sending" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-primary-700 transition-colors disabled:opacity-60">
                        <svg x-show="sending" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                        <span x-text="sending ? 'Processing…' : 'Submit — {{ number_format($service->price, 2) }} {{ currency() }}'"></span>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
        <x-empty-state title="No services available" description="Telegram services are currently disabled. Please check back later or contact support." actionLabel="Contact Support" actionUrl="{{ route('contact') }}" />
    @endif

    {{-- Recent requests --}}
    @if($recent->count())
    <div class="rounded-2xl bg-dark-900 border border-dark-800 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-dark-800">
            <h2 class="text-sm font-semibold text-white">Recent requests</h2>
            <a href="{{ route('telegram.history') }}" class="text-xs text-primary-400 hover:text-primary-300">View all →</a>
        </div>
        <ul class="divide-y divide-dark-800/60">
            @foreach($recent as $req)
            <li>
                <a href="{{ route('telegram.result', $req) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-dark-800/50 transition-colors">
                    <x-status-pill :status="$req->status" :label="$req->status->label()" />
                    <span class="text-sm text-white truncate">{{ $req->telegramService?->name ?? 'Service' }}</span>
                    <span class="text-xs text-dark-500 font-mono truncate">{{ $req->target_identifier }}</span>
                    <span class="ms-auto text-xs text-dark-500 shrink-0">{{ $req->created_at->diffForHumans() }}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection
