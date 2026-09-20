@extends('layouts.app', ['title' => 'Telegram Services - IQAB Admin'])

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Telegram Services</h1>
            <p class="text-sm text-dark-500 mt-1">Manage Telegram tools shown to users at /telegram.</p>
        </div>
        <a href="{{ route('admin.telegram-services.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Service
        </a>
    </div>

    @if($services->count())
        <div class="rounded-xl bg-dark-900 border border-dark-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-dark-800">
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Name</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Type</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Price</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Status</th>
                        <th class="text-right py-3 px-4 text-xs font-medium text-dark-400 uppercase">Actions</th>
                    </tr></thead>
                    <tbody>
                    @foreach($services as $service)
                        <tr class="border-b border-dark-800/50 hover:bg-dark-800/50">
                            <td class="py-3 px-4">
                                <p class="text-sm font-medium text-white">{{ $service->name }}</p>
                                <p class="text-xs text-dark-500 truncate max-w-xs">{{ $service->description }}</p>
                            </td>
                            <td class="py-3 px-4"><span class="inline-flex px-2 py-0.5 rounded text-xs bg-dark-800 text-dark-300">{{ $service->type instanceof \App\Enums\TelegramServiceType ? $service->type->label() : $service->type }}</span></td>
                            <td class="py-3 px-4 text-dark-200">{{ number_format($service->price, 2) }} {{ currency() }}</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $service->is_active ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }}">{{ $service->is_active ? 'Active' : 'Disabled' }}</span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.telegram-services.edit', $service) }}" class="text-primary-400 hover:text-primary-300 text-sm font-medium">Edit</a>
                                    <form method="POST" action="{{ route('admin.telegram-services.destroy', $service) }}" onsubmit="return confirm('Delete this service?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-medium">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-12 text-center">
            <p class="text-sm text-dark-500">No Telegram services yet. Create the first one.</p>
        </div>
    @endif
</div>
@endsection
