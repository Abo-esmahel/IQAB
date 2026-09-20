@extends('layouts.app', ['title' => 'Manage Offers - IQAB Admin'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-white">Offers & Deals</h1>
        <a href="{{ route('admin.offers.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Offer
        </a>
    </div>

    @if($offers->count())
    <div class="rounded-xl bg-dark-900 border border-dark-800 overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-dark-800">
                    <th class="text-left px-4 py-3 font-medium text-dark-400">Offer</th>
                    <th class="text-left px-4 py-3 font-medium text-dark-400">Type</th>
                    <th class="text-left px-4 py-3 font-medium text-dark-400">Price</th>
                    <th class="text-left px-4 py-3 font-medium text-dark-400">Status</th>
                    <th class="text-left px-4 py-3 font-medium text-dark-400">Expires</th>
                    <th class="text-left px-4 py-3 font-medium text-dark-400"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-800">
                @foreach($offers as $offer)
                <tr class="hover:bg-dark-800/50">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if($offer->image)
                                <img src="{{ $offer->image }}" class="h-10 w-10 rounded-lg object-cover" alt="">
                            @else
                                <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-amber-500/20 to-orange-500/20 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                </div>
                            @endif
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-white font-medium">{{ $offer->title }}</p>
                                    @if($offer->badge)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-amber-500/10 text-amber-400">{{ $offer->badge }}</span>
                                    @endif
                                </div>
                                <p class="text-xs text-dark-500">/{{ $offer->slug }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-dark-700 text-dark-300 capitalize">{{ $offer->type }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div>
                            @if($offer->original_price)
                                <p class="text-xs text-dark-500 line-through">{{ number_format($offer->original_price, 2) }} {{ currency() }}</p>
                            @endif
                            <p class="text-white font-semibold">{{ number_format($offer->offer_price, 2) }} {{ currency() }}</p>
                            @if($offer->discount_percent)
                                <p class="text-xs text-emerald-400">{{ number_format($offer->discount_percent, 0) }}% OFF</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        @if($offer->isActiveNow)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400">Active</span>
                        @elseif($offer->is_expired)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-400">Expired</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-dark-700 text-dark-400">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-dark-400 text-xs">
                        {{ $offer->expires_at ? $offer->expires_at->format('M d, Y') : 'Never' }}
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.offers.edit', $offer) }}" class="text-primary-400 hover:text-primary-300">Edit</a>
                        <form method="POST" action="{{ route('admin.offers.destroy', $offer) }}" class="inline" onsubmit="return confirm('Delete this offer?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
    <div class="mt-6">{{ $offers->links() }}</div>
    @else
    <div class="rounded-xl bg-dark-900 border border-dark-800 p-12 text-center">
        <svg class="h-12 w-12 text-dark-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
        <p class="text-dark-400 mb-4">No offers yet</p>
        <a href="{{ route('admin.offers.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
            Create First Offer
        </a>
    </div>
    @endif
</div>
@endsection
