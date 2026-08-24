@extends('layouts.app', ['title' => 'Edit Number - IQAB'])

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-white">Edit Phone Number</h1>
        <a href="{{ route('admin.numbers.index') }}" class="text-sm text-dark-400 hover:text-white transition-colors">&larr; Back to Numbers</a>
    </div>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
        <form method="POST" action="{{ route('admin.numbers.update', $number) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Phone Number</label>
                <input type="text" name="phone_number" value="{{ old('phone_number', $number->phone_number) }}"
                       class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none font-mono">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Country</label>
                    <input type="text" name="country" value="{{ old('country', $number->country) }}"
                           class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Country Code</label>
                    <input type="text" name="country_code" value="{{ old('country_code', $number->country_code) }}"
                           class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Provider</label>
                    <input type="text" name="provider" value="{{ old('provider', $number->provider) }}"
                           class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Provider Number ID</label>
                    <input type="text" name="provider_number_id" value="{{ old('provider_number_id', $number->provider_number_id) }}"
                           class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Price (SAR)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $number->price) }}"
                           class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Status</label>
                    <select name="status"
                            class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                        @foreach($statuses as $case)
                            <option value="{{ $case->value }}" {{ old('status', $number->status->value) === $case->value ? 'selected' : '' }}>{{ $case->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Expires At</label>
                <input type="datetime-local" name="expires_at"
                       value="{{ old('expires_at', $number->expires_at ? $number->expires_at->format('Y-m-d\TH:i') : '') }}"
                       class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none [color-scheme:dark]">
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">
                    Save Changes
                </button>
                <form method="POST" action="{{ route('admin.numbers.destroy', $number) }}" onsubmit="return confirm('Delete this number permanently?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg bg-red-600/10 border border-red-500/30 px-4 py-2.5 text-sm font-medium text-red-400 hover:bg-red-600/20 transition-colors">
                        Delete
                    </button>
                </form>
            </div>
        </form>
    </div>
</div>
@endsection
