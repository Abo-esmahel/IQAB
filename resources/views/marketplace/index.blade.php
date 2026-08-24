@extends('layouts.app', ['title' => 'Marketplace - IQAB'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Marketplace</h1>
            <p class="text-sm text-dark-400 mt-1">Browse our collection of numbers, services, and exclusive offers</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-sm text-dark-500">{{ number_format($allCount) }} items total</span>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex items-center gap-1 p-1 bg-dark-900 border border-dark-800 rounded-xl mb-6 w-fit overflow-x-auto">
        <a href="{{ route('marketplace', ['tab' => 'all'] + request()->query()) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap {{ $tab === 'all' ? 'bg-primary-600 text-white' : 'text-dark-400 hover:text-white' }}">
            All Items <span class="ml-1 text-xs opacity-70">({{ $allCount }})</span>
        </a>
        <a href="{{ route('marketplace', ['tab' => 'offers'] + request()->query()) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap {{ $tab === 'offers' ? 'bg-primary-600 text-white' : 'text-dark-400 hover:text-white' }}">
            🔥 Offers <span class="ml-1 text-xs opacity-70">({{ $offersCount }})</span>
        </a>
        <a href="{{ route('marketplace', ['tab' => 'numbers'] + request()->query()) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap {{ $tab === 'numbers' ? 'bg-primary-600 text-white' : 'text-dark-400 hover:text-white' }}">
            📱 Numbers <span class="ml-1 text-xs opacity-70">({{ $numbersCount }})</span>
        </a>
        <a href="{{ route('marketplace', ['tab' => 'services'] + request()->query()) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap {{ $tab === 'services' ? 'bg-primary-600 text-white' : 'text-dark-400 hover:text-white' }}">
            ⚡ Services <span class="ml-1 text-xs opacity-70">({{ $servicesCount }})</span>
        </a>
    </div>

    {{-- Search (hero) + collapsible filters --}}
    <form method="GET" action="{{ route('marketplace') }}" x-data="{ open: false }" class="mb-6">
        <input type="hidden" name="tab" value="{{ $tab }}">

        {{-- Beautiful search bar --}}
        <div class="group relative flex items-center gap-3 rounded-2xl bg-dark-900 border border-dark-800 p-2 pl-5 shadow-lg shadow-black/30 transition-all focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-primary-500/20">
            <svg class="h-5 w-5 shrink-0 text-dark-500 transition-colors group-focus-within:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
            </svg>
            <input type="text" name="search" placeholder="Search numbers, services, offers..."
                   value="{{ request('search') }}"
                   class="flex-1 bg-transparent text-sm sm:text-base text-white placeholder-dark-500 outline-none">
            <button type="submit" class="shrink-0 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-primary-600/20 transition-all hover:from-primary-500 hover:to-primary-600 hover:shadow-primary-500/30">
                Search
            </button>
        </div>

        {{-- Filters toggle --}}
        <div class="mt-3 flex items-center gap-3">
            <button type="button" @click="open = !open"
                    class="inline-flex items-center gap-2 text-sm font-medium text-dark-400 hover:text-white transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-5 5A1 1 0 0115 13.414V19a1 1 0 01-1.447.894l-2-1A1 1 0 0111 18v-4.586a1 1 0 01-.293-.707l-5-5A1 1 0 015 7.586V4z"/></svg>
                Filters
                <svg class="h-4 w-4 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            @if(request()->hasAny(['search', 'country', 'category', 'min_price', 'max_price']))
                <a href="{{ route('marketplace', ['tab' => $tab]) }}" class="text-sm text-dark-500 hover:text-primary-400 transition-colors">Clear all</a>
            @endif
        </div>

        {{-- Collapsible filter row --}}
        <div x-show="open" x-cloak x-transition.opacity class="mt-3 grid sm:grid-cols-2 lg:grid-cols-4 gap-3 rounded-xl bg-dark-900 border border-dark-800 p-4">
            <input type="number" name="min_price" placeholder="Min Price" value="{{ request('min_price') }}"
                   class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
            <input type="number" name="max_price" placeholder="Max Price" value="{{ request('max_price') }}"
                   class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
            @if($tab === 'all' || $tab === 'numbers')
            <select name="country" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                <option value="">All Countries</option>
                @foreach($countries as $country)
                    <option value="{{ $country }}" {{ request('country') === $country ? 'selected' : '' }}>{{ $country }}</option>
                @endforeach
            </select>
            @endif
            @if($tab === 'all' || $tab === 'services')
            <select name="category" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            @endif
        </div>
    </form>

    {{-- ============================================================ --}}
    {{-- ALL TAB --}}
    {{-- ============================================================ --}}
    @if($tab === 'all')

        {{-- Featured Offers --}}
        @if($offers->count())
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">🔥 Hot Offers</h2>
                <a href="{{ route('marketplace', ['tab' => 'offers'] + request()->query()) }}" class="text-sm text-primary-400 hover:text-primary-300">View All →</a>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($offers as $offer)
                <div class="group relative rounded-xl overflow-hidden border border-amber-500/20 hover:border-amber-500/40 transition-all" style="background: linear-gradient(135deg, rgba(245,158,11,0.08) 0%, rgba(15,23,42,1) 100%);">
                    @if($offer->image)
                        <div class="h-40 bg-dark-800 overflow-hidden">
                            <img src="{{ $offer->image }}" alt="{{ $offer->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    @else
                        <div class="h-32 bg-gradient-to-br from-amber-600/20 to-orange-600/10 flex items-center justify-center">
                            <svg class="h-12 w-12 text-amber-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                    @endif
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2">
                                <h3 class="font-semibold text-white">{{ $offer->title }}</h3>
                                @if($offer->badge)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-500 text-white uppercase">{{ $offer->badge }}</span>
                                @endif
                            </div>
                        </div>
                        @if($offer->description)
                            <p class="text-xs text-dark-400 line-clamp-2 mb-3">{{ $offer->description }}</p>
                        @endif
                        <div class="flex items-end justify-between">
                            <div>
                                @if($offer->original_price)
                                    <p class="text-xs text-dark-500 line-through">{{ number_format($offer->original_price, 2) }} SAR</p>
                                @endif
                                <p class="text-xl font-bold text-amber-400">{{ number_format($offer->offer_price, 2) }} <span class="text-sm text-dark-400">SAR</span></p>
                                @if($offer->discount_percent)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400 mt-1">{{ number_format($offer->discount_percent, 0) }}% OFF</span>
                                @endif
                            </div>
                            @if($offer->cta_url)
                                <a href="{{ $offer->cta_url }}" target="_blank" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600 transition-colors">{{ $offer->cta_text }}</a>
                            @elseif($offer->related_service)
                                <a href="{{ route('services.show', $offer->related_service) }}" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600 transition-colors">{{ $offer->cta_text }}</a>
                            @endif
                        </div>
                        @if($offer->expires_at)
                            <p class="text-[10px] text-dark-500 mt-2">Expires: {{ $offer->expires_at->format('M d, Y H:i') }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Services Section --}}
        @if($services->count())
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">⚡ Services</h2>
                <a href="{{ route('marketplace', ['tab' => 'services'] + request()->query()) }}" class="text-sm text-primary-400 hover:text-primary-300">View All →</a>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($services->take(6) as $service)
                <a href="{{ route('services.show', $service) }}" class="group rounded-xl bg-dark-900 border border-dark-800 overflow-hidden hover:border-dark-700 transition-all">
                    @if($service->image)
                        <div class="h-36 bg-dark-800 overflow-hidden">
                            <img src="{{ $service->image }}" alt="{{ $service->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    @else
                        <div class="h-36 bg-gradient-to-br from-dark-800 to-dark-900 flex items-center justify-center">
                            <svg class="h-10 w-10 text-dark-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                    @endif
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-semibold text-white group-hover:text-primary-400 transition-colors">{{ $service->name }}</h3>
                            <span class="text-sm font-bold text-primary-400 shrink-0">{{ number_format($service->price, 2) }} SAR</span>
                        </div>
                        @if($service->short_description)
                            <p class="text-xs text-dark-400 mt-1 line-clamp-2">{{ $service->short_description }}</p>
                        @endif
                        @if($service->category)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-dark-800 text-dark-300 mt-2">{{ $service->category }}</span>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Numbers Section --}}
        @if($numbers->count())
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">📱 Phone Numbers</h2>
                <a href="{{ route('marketplace', ['tab' => 'numbers'] + request()->query()) }}" class="text-sm text-primary-400 hover:text-primary-300">View All →</a>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($numbers->take(6) as $number)
                <div class="rounded-xl bg-dark-900 border border-dark-800 p-5 hover:border-dark-700 transition-colors">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="text-lg font-mono font-semibold text-white">{{ $number->phone_number }}</p>
                            <p class="text-sm text-dark-400">{{ $number->country }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400">Available</span>
                    </div>
                    <div class="flex items-end justify-between mt-4">
                        <div>
                            <p class="text-xs text-dark-500">Price</p>
                            <p class="text-xl font-bold text-white">{{ number_format($number->price, 2) }} <span class="text-sm text-dark-400">SAR</span></p>
                        </div>
                        <a href="{{ config('app.telegram_contact') }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9.78 18.65l.28-4.23 7.68-6.92c.34-.31-.07-.46-.52-.19L7.74 13.3 3.64 12c-.88-.25-.89-.86.2-1.3l15.97-6.16c.73-.33 1.43.18 1.15 1.3l-2.72 12.81c-.19.91-.74 1.13-1.5.71L12.6 16.3l-1.99 1.93c-.23.23-.42.42-.83.42z"/></svg>
                            Buy via Telegram
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(!$numbers->count() && !$services->count() && !$offers->count())
            <x-empty-state title="No items found" description="Try adjusting your search or filters." />
        @endif

    {{-- ============================================================ --}}
    {{-- OFFERS TAB --}}
    {{-- ============================================================ --}}
    @elseif($tab === 'offers')
        @if($offers->count())
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($offers as $offer)
                <div class="group relative rounded-xl overflow-hidden border border-amber-500/20 hover:border-amber-500/40 transition-all" style="background: linear-gradient(135deg, rgba(245,158,11,0.08) 0%, rgba(15,23,42,1) 100%);">
                    @if($offer->image)
                        <div class="h-44 bg-dark-800 overflow-hidden">
                            <img src="{{ $offer->image }}" alt="{{ $offer->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    @else
                        <div class="h-36 bg-gradient-to-br from-amber-600/20 to-orange-600/10 flex items-center justify-center">
                            <svg class="h-14 w-14 text-amber-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                    @endif
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-semibold text-white text-lg">{{ $offer->title }}</h3>
                                @if($offer->badge)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500 text-white uppercase">{{ $offer->badge }}</span>
                                @endif
                            </div>
                        </div>
                        @if($offer->description)
                            <p class="text-sm text-dark-400 line-clamp-3 mb-4">{{ $offer->description }}</p>
                        @endif
                        <div class="flex items-end justify-between">
                            <div>
                                @if($offer->original_price)
                                    <p class="text-sm text-dark-500 line-through">{{ number_format($offer->original_price, 2) }} SAR</p>
                                @endif
                                <p class="text-2xl font-bold text-amber-400">{{ number_format($offer->offer_price, 2) }} <span class="text-sm text-dark-400">SAR</span></p>
                                @if($offer->discount_percent)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-emerald-500/20 text-emerald-400 mt-1">{{ number_format($offer->discount_percent, 0) }}% OFF</span>
                                @endif
                            </div>
                            @if($offer->cta_url)
                                <a href="{{ $offer->cta_url }}" target="_blank" class="rounded-lg bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-amber-600 transition-colors">{{ $offer->cta_text }}</a>
                            @elseif($offer->related_service)
                                <a href="{{ route('services.show', $offer->related_service) }}" class="rounded-lg bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-amber-600 transition-colors">{{ $offer->cta_text }}</a>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 mt-3 text-[10px] text-dark-500">
                            @if($offer->expires_at)
                                <span>Expires: {{ $offer->expires_at->format('M d, Y') }}</span>
                            @endif
                            @if($offer->usage_limit)
                                <span>{{ $offer->used_count }}/{{ $offer->usage_limit }} used</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <x-empty-state title="No offers available" description="Check back later for exclusive deals." />
        @endif

    {{-- ============================================================ --}}
    {{-- NUMBERS TAB --}}
    {{-- ============================================================ --}}
    @elseif($tab === 'numbers')
        @if($numbers->count())
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($numbers as $number)
                <div class="rounded-xl bg-dark-900 border border-dark-800 p-5 hover:border-dark-700 transition-colors">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="text-lg font-mono font-semibold text-white">{{ $number->phone_number }}</p>
                            <p class="text-sm text-dark-400">{{ $number->country }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400">Available</span>
                    </div>
                    <div class="flex items-end justify-between mt-4">
                        <div>
                            <p class="text-xs text-dark-500">Price</p>
                            <p class="text-xl font-bold text-white">{{ number_format($number->price, 2) }} <span class="text-sm text-dark-400">SAR</span></p>
                        </div>
                        <a href="{{ config('app.telegram_contact') }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9.78 18.65l.28-4.23 7.68-6.92c.34-.31-.07-.46-.52-.19L7.74 13.3 3.64 12c-.88-.25-.89-.86.2-1.3l15.97-6.16c.73-.33 1.43.18 1.15 1.3l-2.72 12.81c-.19.91-.74 1.13-1.5.71L12.6 16.3l-1.99 1.93c-.23.23-.42.42-.83.42z"/></svg>
                            Buy via Telegram
                        </a>
                    </div>
                    @if($number->expires_at)
                        <p class="text-xs text-dark-500 mt-3">Expires: {{ $number->expires_at->format('M d, Y') }}</p>
                    @endif
                </div>
                @endforeach
            </div>
            <div class="mt-8">{{ $numbers->links() }}</div>
        @else
            <x-empty-state title="No numbers available" description="Check back later for new numbers." />
        @endif

    {{-- ============================================================ --}}
    {{-- SERVICES TAB --}}
    {{-- ============================================================ --}}
    @elseif($tab === 'services')
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
                            <span class="text-sm font-bold text-primary-400 shrink-0">{{ number_format($service->price, 2) }} SAR</span>
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
            <div class="mt-8">{{ $services->links() }}</div>
        @else
            <x-empty-state title="No services available" description="Check back later for new services." />
        @endif
    @endif
</div>
@endsection
