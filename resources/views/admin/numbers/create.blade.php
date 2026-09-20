@extends('layouts.app', ['title' => 'Publish Number - IQAB'])

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-admin.header
        title="Publish Phone Number"
        subtitle="Add a single virtual number or publish a batch."
        backRoute="admin.numbers.index"
        backLabel="Numbers"
        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'
    />

    <div class="grid gap-6 lg:grid-cols-1 sm:grid-cols-2">
        {{-- Single number --}}
        <x-admin.card :padding="'p-6'" x-data="{ status: 'available' }">
            <h2 class="text-lg font-semibold text-white mb-4">Single Number</h2>
            <form method="POST" action="{{ route('admin.numbers.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Phone Number</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                           placeholder="201234567890"
                           class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Country</label>
                        <input type="text" name="country" value="{{ old('country') }}"
                               placeholder="Saudi Arabia"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Country Code</label>
                        <input type="text" name="country_code" value="{{ old('country_code') }}"
                               placeholder="+966"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Provider</label>
                        <input type="text" name="provider" value="{{ old('provider') }}"
                               placeholder="default"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Provider Number ID</label>
                        <input type="text" name="provider_number_id" value="{{ old('provider_number_id') }}"
                               placeholder="prov_123"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Price ({{ currency() }})</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}"
                               placeholder="25.00"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Status</label>
                        <select name="status" x-model="status"
                                class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                            @foreach(\App\Enums\PhoneNumberStatus::cases() as $case)
                                <option value="{{ $case->value }}" {{ old('status', 'available') === $case->value ? 'selected' : '' }}>{{ $case->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Expires At</label>
                    <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"
                           class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none [color-scheme:dark]">
                </div>
                <button type="submit" class="w-full rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 hover:shadow-[0_0_24px_rgba(216,156,43,0.25)] transition-all">
                    Publish Number
                </button>
            </form>
        </x-admin.card>

        {{-- Bulk publish --}}
        <x-admin.card :padding="'p-6'">
            <h2 class="text-lg font-semibold text-white mb-1">Bulk Publish</h2>
            <p class="text-xs text-dark-500 mb-4">Paste one number per line. Shared settings below apply to all.</p>
            <form method="POST" action="{{ route('admin.numbers.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Numbers (one per line)</label>
                    <textarea name="bulk" rows="6" placeholder="201234567890&#10;201234567891"
                              class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none font-mono">{{ old('bulk') }}</textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Country</label>
                        <input type="text" name="country" value="{{ old('country') }}"
                               placeholder="Saudi Arabia"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Country Code</label>
                        <input type="text" name="country_code" value="{{ old('country_code') }}"
                               placeholder="+966"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Price ({{ currency() }})</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}"
                               placeholder="25.00"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1.5">Status</label>
                        <select name="status"
                                class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                            @foreach(\App\Enums\PhoneNumberStatus::cases() as $case)
                                <option value="{{ $case->value }}" {{ old('status', 'available') === $case->value ? 'selected' : '' }}>{{ $case->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Expires At</label>
                    <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"
                           class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none [color-scheme:dark]">
                </div>
                <button type="submit" class="w-full rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 hover:shadow-[0_0_24px_rgba(216,156,43,0.25)] transition-all">
                    Publish Bulk
                </button>
            </form>
        </x-admin.card>
    </div>
</div>
@endsection
