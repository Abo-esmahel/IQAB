@extends('layouts.app', ['title' => 'Edit Telegram Service - IQAB Admin'])

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-admin.header
        :title="'Edit: ' . $telegramService->name"
        subtitle="Update this Telegram tool."
        backRoute="admin.telegram-services.index"
        backLabel="Telegram Services"
        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'
    />

    <x-admin.card>
        <form method="POST" action="{{ route('admin.telegram-services.update', $telegramService) }}" class="space-y-5">
            @csrf @method('PUT')

            <x-forms.input name="name" label="Service Name" :value="$telegramService->name" required />

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Type <span class="text-red-400">*</span></label>
                <select name="type" required class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white focus:border-primary-500 outline-none">
                    @foreach($types as $type)
                        <option value="{{ $type->value }}" {{ old('type', $telegramService->type instanceof \App\Enums\TelegramServiceType ? $telegramService->type->value : $telegramService->type) === $type->value ? 'selected' : '' }}>{{ $type->label() }} ({{ $type->value }})</option>
                    @endforeach
                </select>
                @error('type')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Description</label>
                <textarea name="description" rows="3" class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">{{ old('description', $telegramService->description) }}</textarea>
            </div>

            <x-forms.input name="price" label="Price" type="number" step="0.01" min="0" :value="$telegramService->price" required />

            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $telegramService->is_active ? '1' : '0') === '1' ? 'checked' : '' }} class="h-4 w-4 text-primary-600 bg-dark-800 border-dark-600 rounded focus:ring-primary-500">
                <label class="text-sm text-dark-300">Active (visible to users)</label>
            </div>

            <div class="flex justify-end gap-3 pt-5 border-t border-dark-800">
                <a href="{{ route('admin.telegram-services.index') }}" class="rounded-lg border border-dark-700 px-4 py-2 text-sm font-medium text-dark-300 hover:bg-dark-800 transition-colors">Cancel</a>
                <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700 transition-all">Update Service</button>
            </div>
        </form>
    </x-admin.card>
</div>
@endsection
