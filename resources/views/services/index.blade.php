@extends('layouts.app', ['title' => 'Services - IQAB'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-white">Services</h1>
    </div>

    @if($featured->count())
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-white mb-4">Featured</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($featured as $service)
            <a href="{{ route('services.show', $service) }}" class="group rounded-xl bg-dark-900 border border-dark-800 overflow-hidden hover:border-primary-500/50 transition-all">
                @if($service->image)
                    <div class="h-40 bg-dark-800 overflow-hidden">
                        <img src="{{ $service->image }}" alt="{{ $service->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                @else
                    <div class="h-40 bg-gradient-to-br from-primary-600/20 to-primary-800/20 flex items-center justify-center">
                        <svg class="h-12 w-12 text-primary-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                @endif
                <div class="p-4">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-semibold text-white group-hover:text-primary-400 transition-colors">{{ $service->name }}</h3>
                        <span class="text-sm font-bold text-primary-400 shrink-0">{{ number_format($service->price, 2) }} {{ currency() }}</span>
                    </div>
                    @if($service->short_description)
                        <p class="text-sm text-dark-400 mt-2 line-clamp-2">{{ $service->short_description }}</p>
                    @endif
                    @if($service->category)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-dark-800 text-dark-300 mt-3">{{ $service->category }}</span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-4 mb-6">
        <form method="GET" action="{{ route('services.index') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" placeholder="Search services..." value="{{ request('search') }}"
                   class="flex-1 rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
            <select name="category" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-primary-700 transition-colors">Search</button>
        </form>
    </div>

    @if($services->count())
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($services as $service)
            <a href="{{ route('services.show', $service) }}" class="group rounded-xl bg-dark-900 border border-dark-800 overflow-hidden hover:border-dark-700 transition-all">
                @if($service->image)
                    <div class="h-40 bg-dark-800 overflow-hidden">
                        <img src="{{ $service->image }}" alt="{{ $service->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                @else
                    <div class="h-40 bg-gradient-to-br from-dark-800 to-dark-900 flex items-center justify-center">
                        <svg class="h-12 w-12 text-dark-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                @endif
                <div class="p-4">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-semibold text-white group-hover:text-primary-400 transition-colors">{{ $service->name }}</h3>
                        <span class="text-sm font-bold text-primary-400 shrink-0">{{ number_format($service->price, 2) }} {{ currency() }}</span>
                    </div>
                    @if($service->short_description)
                        <p class="text-sm text-dark-400 mt-2 line-clamp-2">{{ $service->short_description }}</p>
                    @endif
                    @if($service->category)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-dark-800 text-dark-300 mt-3">{{ $service->category }}</span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-8">{{ $services->withQueryString()->links() }}</div>
    @else
        <x-empty-state title="No services available" description="Check back later for new services." />
    @endif
</div>
@endsection
