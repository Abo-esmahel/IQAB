@extends('layouts.app', ['title' => 'Edit Service - IQAB Admin'])

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-admin.header
        title="Edit Service"
        subtitle="Update the marketplace listing for this service."
        backRoute="admin.services.index"
        backLabel="Services"
        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'
    />

    <x-admin.card>
        <form method="POST" action="{{ route('admin.services.update', $service) }}" class="space-y-6" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-forms.input name="name" label="Service Name" :value="$service->name" required />
                <x-forms.input name="slug" label="Slug" :value="$service->slug" hint="Changing slug will update the URL" />
            </div>

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Description</label>
                <textarea name="description" rows="4" class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors" placeholder="Detailed description of the service...">{{ old('description', $service->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-forms.input name="short_description" label="Short Description" :value="$service->short_description" />
                <x-forms.input name="category" label="Category" :value="$service->category" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-forms.input name="price" :label="'Price (' . currency() . ')'" type="number" step="0.01" min="0" :value="$service->price" required />
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

            <div class="flex justify-end gap-3 pt-5 border-t border-dark-800">
                <a href="{{ route('admin.services.index') }}" class="rounded-lg border border-dark-700 px-4 py-2 text-sm font-medium text-dark-300 hover:bg-dark-800 transition-colors">Cancel</a>
                <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700 hover:shadow-[0_0_24px_rgba(216,156,43,0.25)] transition-all">Update Service</button>
            </div>
        </form>
    </x-admin.card>
</div>
@endsection