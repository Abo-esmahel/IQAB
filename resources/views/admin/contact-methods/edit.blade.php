@extends('layouts.app', ['title' => 'Edit Contact Method - IQAB Admin'])

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-admin.header
        title="Edit Contact Method"
        subtitle="Update the support channel details for “{{ $contactMethod->name }}”."
        backRoute="admin.contact-methods.index"
        backLabel="Contact Methods"
        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'
    />

    <x-admin.card>
        <form method="POST" action="{{ route('admin.contact-methods.update', $contactMethod) }}" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $contactMethod->name) }}" required placeholder="e.g. WhatsApp Support"
                       class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors">
            </div>

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Type <span class="text-red-400">*</span></label>
                <select name="type" required class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors">
                    <option value="">Select type...</option>
                    @foreach(['whatsapp', 'telegram', 'email', 'phone', 'twitter', 'instagram', 'discord', 'facebook', 'other'] as $type)
                        <option value="{{ $type }}" {{ old('type', $contactMethod->type) === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Value <span class="text-red-400">*</span></label>
                <input type="text" name="value" value="{{ old('value', $contactMethod->value) }}" required placeholder="e.g. +966501234567 or support@example.com"
                       class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors">
            </div>

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">URL (optional)</label>
                <input type="url" name="url" value="{{ old('url', $contactMethod->url) }}" placeholder="https://wa.me/966501234567"
                       class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors">
            </div>

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Description (optional)</label>
                <textarea name="description" rows="2" placeholder="Brief description..."
                          class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors">{{ old('description', $contactMethod->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Brand Color</label>
                    <input type="color" name="color" value="{{ old('color', $contactMethod->color ?? '#25D366') }}"
                           class="w-full h-10 rounded-lg bg-dark-800 border border-dark-700 px-2 py-1 cursor-pointer">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $contactMethod->sort_order) }}" min="0"
                           class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors">
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $contactMethod->is_active) ? 'checked' : '' }}
                       class="h-4 w-4 text-primary-600 bg-dark-800 border-dark-600 rounded focus:ring-primary-500">
                <label class="text-sm text-dark-300">Active (visible to users)</label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-dark-800">
                <a href="{{ route('admin.contact-methods.index') }}" class="rounded-lg border border-dark-700 px-4 py-2 text-sm font-medium text-dark-300 hover:bg-dark-800 transition-colors">Cancel</a>
                <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700 hover:shadow-[0_0_24px_rgba(216,156,43,0.25)] transition-all">Update Contact Method</button>
            </div>
        </form>
    </x-admin.card>
</div>
@endsection
