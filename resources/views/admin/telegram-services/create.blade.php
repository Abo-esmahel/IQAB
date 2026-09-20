@extends('layouts.app', ['title' => 'Create Telegram Service - IQAB Admin'])

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-admin.header
        title="Create Telegram Service"
        subtitle="Add a new Telegram tool for users."
        backRoute="admin.telegram-services.index"
        backLabel="Telegram Services"
        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'
    />

    <x-admin.card>
        <form method="POST" action="{{ route('admin.telegram-services.store') }}" class="space-y-5">
            @csrf

            <x-forms.input name="name" label="Service Name" required />

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Type <span class="text-red-400">*</span></label>
                <select name="type" required class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white focus:border-primary-500 outline-none">
                    <option value="">Select type</option>
                    @foreach($types as $type)
                        <option value="{{ $type->value }}" {{ old('type') === $type->value ? 'selected' : '' }}>{{ $type->label() }} ({{ $type->value }})</option>
                    @endforeach
                </select>
                @error('type')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Description</label>
                <textarea name="description" rows="3" class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none" placeholder="Describe what this tool does...">{{ old('description') }}</textarea>
            </div>

            <x-forms.input name="price" label="Price" type="number" step="0.01" min="0" required :hint="'Price in ' . currency()" />

            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') === '1' ? 'checked' : '' }} class="h-4 w-4 text-primary-600 bg-dark-800 border-dark-600 rounded focus:ring-primary-500">
                <label class="text-sm text-dark-300">Active (visible to users)</label>
            </div>

            <div class="flex justify-end gap-3 pt-5 border-t border-dark-800">
                <a href="{{ route('admin.telegram-services.index') }}" class="rounded-lg border border-dark-700 px-4 py-2 text-sm font-medium text-dark-300 hover:bg-dark-800 transition-colors">Cancel</a>
                <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700 transition-all">Create Service</button>
            </div>
        </form>
    </x-admin.card>
</div>
@endsection
