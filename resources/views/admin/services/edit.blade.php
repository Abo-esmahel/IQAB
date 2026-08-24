@extends('layouts.app', ['title' => 'Edit Service - IQAB Admin'])

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('admin.services.index') }}" class="inline-flex items-center gap-1 text-sm text-dark-400 hover:text-white mb-6">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Services
    </a>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
        <h1 class="text-lg font-semibold text-white mb-6">Edit: {{ $service->name }}</h1>

        <form method="POST" action="{{ route('admin.services.update', $service) }}" class="space-y-5" enctype="multipart/form-data">
            @csrf @method('PUT')
            <x-forms.input name="name" label="Service Name" :value="$service->name" required />

            <x-forms.input name="slug" label="Slug" :value="$service->slug" hint="Changing slug will update the URL" />

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Description</label>
                <textarea name="description" rows="4" class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors">{{ old('description', $service->description) }}</textarea>
            </div>

            <x-forms.input name="short_description" label="Short Description" :value="$service->short_description" />

            <x-forms.input name="category" label="Category" :value="$service->category" />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-forms.input name="price" label="Price (SAR)" type="number" step="0.01" min="0" :value="$service->price" required />
                <x-forms.input name="sort_order" label="Sort Order" type="number" :value="$service->sort_order" />
            </div>

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Image URL</label>
                <input type="url" name="image" value="{{ old('image', $service->image) }}" placeholder="https://example.com/image.jpg"
                       class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors">
                @if($service->image)
                    <div class="mt-2">
                        <img src="{{ $service->image }}" class="h-16 rounded-lg object-cover" alt="Current image">
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                       class="h-4 w-4 text-primary-600 bg-dark-800 border-dark-600 rounded focus:ring-primary-500">
                <label class="text-sm text-dark-300">Active (visible to users)</label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-dark-800">
                <a href="{{ route('admin.services.index') }}" class="rounded-lg border border-dark-700 px-4 py-2 text-sm font-medium text-dark-300 hover:bg-dark-800 transition-colors">Cancel</a>
                <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">Update Service</button>
            </div>
        </form>
    </div>
</div>
@endsection
