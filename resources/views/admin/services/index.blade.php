@extends('layouts.app', ['title' => 'Manage Services - IQAB Admin'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-white">Services</h1>
        <a href="{{ route('admin.services.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Service
        </a>
    </div>

    @if($services->count())
<div class="rounded-xl bg-dark-900 border border-dark-800 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-dark-800">
                    <th class="text-left px-4 py-3 font-medium text-dark-400">Service</th>
                    <th class="text-left px-4 py-3 font-medium text-dark-400">Category</th>
                    <th class="text-left px-4 py-3 font-medium text-dark-400">Price</th>
                    <th class="text-left px-4 py-3 font-medium text-dark-400">Status</th>
                    <th class="text-left px-4 py-3 font-medium text-dark-400">Purchases</th>
                    <th class="text-left px-4 py-3 font-medium text-dark-400"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-800">
                @foreach($services as $service)
                <tr class="hover:bg-dark-800/50">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if($service->image)
                                <img src="{{ $service->image }}" class="h-8 w-8 rounded-lg object-cover" alt="">
                            @else
                                <div class="h-8 w-8 rounded-lg bg-dark-700 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-dark-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                            @endif
                            <div>
                                <p class="text-white font-medium">{{ $service->name }}</p>
                                <p class="text-xs text-dark-500">/{{ $service->slug }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-dark-300">{{ $service->category ?? '-' }}</td>
                    <td class="px-4 py-3 text-white font-medium">{{ number_format($service->price, 2) }} SAR</td>
                    <td class="px-4 py-3">
                        @if($service->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400">Active</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-dark-700 text-dark-400">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-dark-300">{{ $service->purchases_count ?? $service->purchases()->count() }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.services.edit', $service) }}" class="text-primary-400 hover:text-primary-300">Edit</a>
                        <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="inline" onsubmit="return confirm('Delete this service?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
    <div class="mt-6">{{ $services->links() }}</div>
    @else
    <x-empty-state title="No services yet" description="Create your first service to get started." />
    @endif
</div>
@endsection
