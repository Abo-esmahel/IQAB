@extends('layouts.app', ['title' => $service->name . ' - IQAB'])

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('services.index') }}" class="inline-flex items-center gap-1 text-sm text-dark-400 hover:text-white mb-6">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Services
    </a>

    <div class="rounded-xl bg-dark-900 border border-dark-800 overflow-hidden">
        @if($service->image)
            <div class="h-48 sm:h-64 bg-dark-800 overflow-hidden">
                <img loading="lazy" decoding="async" src="{{ $service->image }}" alt="{{ $service->name }}" class="w-full h-full object-cover">
            </div>
        @else
            <div class="h-48 bg-gradient-to-br from-primary-600/20 to-primary-800/10 flex items-center justify-center">
                <svg class="h-16 w-16 text-primary-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        @endif

        <div class="p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $service->name }}</h1>
                    @if($service->category)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-dark-800 text-dark-300 mt-2">{{ $service->category }}</span>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-primary-400">{{ number_format($service->price, 2) }}</p>
                    <p class="text-sm text-dark-400">{{ currency() }}</p>
                </div>
            </div>

            @if($service->description)
                <div class="prose prose-invert max-w-none mb-6">
                    <p class="text-dark-300 whitespace-pre-wrap">{{ $service->description }}</p>
                </div>
            @endif

            <div class="rounded-xl bg-dark-800 border border-dark-700 p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Purchase Service</h2>
                <div class="space-y-4">
                    <p class="text-sm text-dark-400">Total: <span class="font-semibold text-white">{{ number_format($service->price, 2) }} {{ currency() }}</span></p>
                    <a href="{{ telegram_contact() }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9.78 18.65l.28-4.23 7.68-6.92c.34-.31-.07-.46-.52-.19L7.74 13.3 3.64 12c-.88-.25-.89-.86.2-1.3l15.97-6.16c.73-.33 1.43.18 1.15 1.3l-2.72 12.81c-.19.91-.74 1.13-1.5.71L12.6 16.3l-1.99 1.93c-.23.23-.42.42-.83.42z"/></svg>
                        Purchase via Telegram
                    </a>
                    <p class="text-xs text-dark-500">Contact us on Telegram to complete your order.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
