@extends('layouts.app', ['title' => $service->name . ' - IQAB'])

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('services.index') }}" class="inline-flex items-center gap-1 text-sm text-dark-400 hover:text-white mb-6">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Services
    </a>

    <div class="rounded-xl bg-dark-900 border border-dark-800 overflow-hidden">
        @if($service->image)
            <div class="h-64 bg-dark-800 overflow-hidden">
                <img src="{{ $service->image }}" alt="{{ $service->name }}" class="w-full h-full object-cover">
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
                    <p class="text-sm text-dark-400">SAR</p>
                </div>
            </div>

            @if($service->description)
                <div class="prose prose-invert max-w-none mb-6">
                    <p class="text-dark-300 whitespace-pre-wrap">{{ $service->description }}</p>
                </div>
            @endif

            <div class="rounded-xl bg-dark-800 border border-dark-700 p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Purchase Service</h2>
                <form method="POST" action="{{ route('services.purchase', $service) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Required Information</label>
                        <input type="text" name="input_data" required placeholder="Enter the required data for this service"
                               class="w-full rounded-lg bg-dark-900 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors">
                        <p class="text-xs text-dark-500 mt-1">Provide the necessary input for this service to process.</p>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <p class="text-sm text-dark-400">Total: <span class="font-semibold text-white">{{ number_format($service->price, 2) }} SAR</span></p>
                        <button type="submit" onclick="return confirm('Purchase this service for {{ number_format($service->price, 2) }} SAR?')"
                                class="rounded-lg bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">
                            Purchase Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
