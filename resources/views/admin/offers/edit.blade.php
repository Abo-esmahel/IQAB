@extends('layouts.app', ['title' => 'Edit Offer - IQAB Admin'])

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('admin.offers.index') }}" class="inline-flex items-center gap-1 text-sm text-dark-400 hover:text-white mb-6">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Offers
    </a>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
        <h1 class="text-lg font-semibold text-white mb-6">Edit: {{ $offer->title }}</h1>

        <form method="POST" action="{{ route('admin.offers.update', $offer) }}" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Title <span class="text-red-400">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $offer->title) }}" required
                           class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Badge</label>
                    <input type="text" name="badge" value="{{ old('badge', $offer->badge) }}"
                           class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Type <span class="text-red-400">*</span></label>
                    <select name="type" required class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white focus:border-primary-500 outline-none">
                        @foreach(['other', 'service', 'number', 'bundle'] as $type)
                            <option value="{{ $type }}" {{ old('type', $offer->type) === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">{{ old('description', $offer->description) }}</textarea>
                </div>
            </div>

            <div class="border-t border-dark-800 pt-5">
                <h3 class="text-sm font-semibold text-white mb-3">Pricing</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Original Price (SAR)</label>
                        <input type="number" step="0.01" name="original_price" value="{{ old('original_price', $offer->original_price) }}"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Offer Price (SAR) <span class="text-red-400">*</span></label>
                        <input type="number" step="0.01" name="offer_price" value="{{ old('offer_price', $offer->offer_price) }}" required
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Discount %</label>
                        <input type="number" step="0.01" name="discount_percent" value="{{ old('discount_percent', $offer->discount_percent) }}" min="0" max="100"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                    </div>
                </div>
            </div>

            <div class="border-t border-dark-800 pt-5">
                <h3 class="text-sm font-semibold text-white mb-3">Link to (Optional)</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Related Service</label>
                        <select name="related_service_id" class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white focus:border-primary-500 outline-none">
                            <option value="">None</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('related_service_id', $offer->related_service_id) == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Related Number</label>
                        <select name="related_number_id" class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white focus:border-primary-500 outline-none">
                            <option value="">None</option>
                            @foreach($numbers as $number)
                                <option value="{{ $number->id }}" {{ old('related_number_id', $offer->related_number_id) == $number->id ? 'selected' : '' }}>{{ $number->phone_number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="border-t border-dark-800 pt-5">
                <h3 class="text-sm font-semibold text-white mb-3">Call to Action & Timing</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Button Text</label>
                        <input type="text" name="cta_text" value="{{ old('cta_text', $offer->cta_text) }}"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Button URL</label>
                        <input type="url" name="cta_url" value="{{ old('cta_url', $offer->cta_url) }}"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Starts At</label>
                        <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $offer->starts_at ? $offer->starts_at->format('Y-m-d\TH:i') : '') }}"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white focus:border-primary-500 outline-none [color-scheme:dark]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Expires At</label>
                        <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $offer->expires_at ? $offer->expires_at->format('Y-m-d\TH:i') : '') }}"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white focus:border-primary-500 outline-none [color-scheme:dark]">
                    </div>
                </div>
            </div>

            <div class="border-t border-dark-800 pt-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Image URL</label>
                        <input type="url" name="image" value="{{ old('image', $offer->image) }}"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Usage Limit</label>
                        <input type="number" name="usage_limit" value="{{ old('usage_limit', $offer->usage_limit) }}" min="1"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $offer->sort_order) }}" min="0"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white focus:border-primary-500 outline-none">
                    </div>
                    <div class="flex items-end gap-4 pb-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $offer->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary-600 bg-dark-800 border-dark-600 rounded focus:ring-primary-500">
                            <span class="text-sm text-dark-300">Active</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $offer->is_featured) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary-600 bg-dark-800 border-dark-600 rounded focus:ring-primary-500">
                            <span class="text-sm text-dark-300">Featured</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-dark-800">
                <a href="{{ route('admin.offers.index') }}" class="rounded-lg border border-dark-700 px-4 py-2 text-sm font-medium text-dark-300 hover:bg-dark-800 transition-colors">Cancel</a>
                <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">Update Offer</button>
            </div>
        </form>
    </div>
</div>
@endsection
