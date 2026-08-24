@extends('layouts.app', ['title' => 'Telegram Tools - IQAB'])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-white">Telegram Tools</h1>
        <a href="{{ route('telegram.history') }}" class="text-sm text-primary-400 hover:text-primary-300">Request History</a>
    </div>

    <div class="grid sm:grid-cols-3 gap-4 mb-8">
        @foreach($services as $service)
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6" x-data="{ showForm: false }">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-white">{{ $service->name }}</h3>
                <p class="text-sm text-dark-400 mt-1">{{ $service->description }}</p>
                <p class="text-xl font-bold text-primary-400 mt-3">{{ number_format($service->price, 2) }} SAR</p>
            </div>
            <button @click="showForm = !showForm" class="w-full rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">
                Use Service
            </button>

            <div x-show="showForm" x-cloak x-transition class="mt-4 pt-4 border-t border-dark-800">
                <form method="POST" action="{{ route('telegram.submit') }}">
                    @csrf
                    <input type="hidden" name="telegram_service_id" value="{{ $service->id }}">
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-dark-400 mb-1">Target (Username/Phone/ID)</label>
                        <input type="text" name="target_identifier" required placeholder="@username or phone"
                               class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
                    </div>
                    <button type="submit" onclick="return confirm('Submit this request for {{ number_format($service->price, 2) }} SAR?')"
                            class="w-full rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
                        Submit Request
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
