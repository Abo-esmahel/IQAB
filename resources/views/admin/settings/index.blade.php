@extends('layouts.app', ['title' => 'System Settings - IQAB'])

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">System Settings</h1>
            <p class="text-sm text-dark-500 mt-1">Service credentials and initial system data. These override <code class="text-dark-400">.env</code> values at runtime.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        @foreach($groups as $groupKey => $group)
            <div class="rounded-xl bg-dark-900 border border-dark-800 p-6 mb-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="h-9 w-9 rounded-lg bg-primary-600/15 flex items-center justify-center">
                        <svg class="h-5 w-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($group['icon'] === 'credit-card')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            @elseif($group['icon'] === 'device-phone-mobile')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            @elseif($group['icon'] === 'paper-airplane')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            @endif
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-white">{{ $group['label'] }}</h2>
                </div>

                <div class="space-y-5">
                    @foreach($group['settings'] as $key => $field)
                        @php
                            $segments = explode('.', $key, 2);
                            $fieldName = 'settings[' . ($segments[0] ?? $key) . '][' . ($segments[1] ?? '') . ']';
                            $oldName = 'settings.' . $key;
                        @endphp
                        <div>
                            <label for="{{ $key }}" class="block text-sm font-medium text-dark-200 mb-1.5">
                                {{ $field['label'] }}
                                @if($field['type'] === 'secret')
                                    <span class="ml-1 text-xs font-normal text-dark-500">(hidden)</span>
                                @endif
                            </label>

                            @if($field['type'] === 'boolean')
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="{{ $fieldName }}" id="{{ $key }}" value="1"
                                           {{ $field['value'] ? 'checked' : '' }}
                                           class="rounded bg-dark-800 border-dark-700 text-primary-600 focus:ring-primary-500">
                                    <span class="text-sm text-dark-400">Enable</span>
                                </label>
                            @elseif($field['type'] === 'secret')
                                <input type="password" name="{{ $fieldName }}" id="{{ $key }}" autocomplete="new-password"
                                       placeholder="{{ $field['placeholder'] }}"
                                       class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                            @else
                                <input type="{{ $field['type'] === 'integer' ? 'number' : ($field['type'] === 'url' ? 'url' : 'text') }}"
                                       name="{{ $fieldName }}" id="{{ $key }}"
                                       value="{{ old($oldName, $field['value']) }}"
                                       placeholder="{{ $field['placeholder'] ?? '' }}"
                                       class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                            @endif

                            @if(!empty($field['description']))
                                <p class="mt-1 text-xs text-dark-500">{{ $field['description'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.dashboard') }}" class="rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm font-medium text-dark-300 hover:text-white transition-colors">Cancel</a>
            <button type="submit" class="rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-700 transition-colors">Save Settings</button>
        </div>
    </form>
</div>
@endsection
