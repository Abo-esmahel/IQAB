@extends('layouts.app', ['title' => 'Manage Services - IQAB Admin'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-admin.header
        title="Services"
        subtitle="Manage the digital services offered on the marketplace."
        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>'
    >
        <x-slot name="action">
            <a href="{{ route('admin.services.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 hover:shadow-[0_0_24px_rgba(216,156,43,0.25)] transition-all">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Service
            </a>
        </x-slot>
    </x-admin.header>

    @if($services->count())
    <x-admin.card :padding="'p-0 sm:p-0'">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-dark-800">
                        <th class="text-left px-6 py-3.5 font-medium text-dark-400 text-xs uppercase tracking-wider">Service</th>
                        <th class="text-left px-6 py-3.5 font-medium text-dark-400 text-xs uppercase tracking-wider">Category</th>
                        <th class="text-left px-6 py-3.5 font-medium text-dark-400 text-xs uppercase tracking-wider">Price</th>
                        <th class="text-left px-6 py-3.5 font-medium text-dark-400 text-xs uppercase tracking-wider">Status</th>
                        <th class="text-left px-6 py-3.5 font-medium text-dark-400 text-xs uppercase tracking-wider hidden sm:table-cell">Purchases</th>
                        <th class="text-right px-6 py-3.5 font-medium text-dark-400 text-xs uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-800/60">
                    @foreach($services as $service)
                    <tr class="hover:bg-dark-800/40 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($service->image)
                                    <img src="{{ $service->image }}" class="h-9 w-9 rounded-lg object-cover ring-1 ring-white/5" alt="">
                                @else
                                    <div class="h-9 w-9 rounded-lg bg-gradient-to-br from-dark-700 to-dark-800 border border-dark-700 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-primary-400/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-white font-medium truncate">{{ $service->name }}</p>
                                    <p class="text-xs text-dark-500 truncate">/{{ $service->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-dark-300">{{ $service->category ?? '-' }}</td>
                        <td class="px-6 py-4 text-white font-medium whitespace-nowrap">{{ number_format($service->price, 2) }} {{ currency() }}</td>
                        <td class="px-6 py-4">
                            @if($service->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 ring-1 ring-emerald-500/20">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-dark-700 text-dark-400 ring-1 ring-dark-600">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-dark-300 hidden sm:table-cell">{{ $service->purchases_count ?? $service->purchases()->count() }}</td>
                        <td class="px-6 py-4 text-end whitespace-nowrap">
                            <a href="{{ route('admin.services.edit', $service) }}" class="text-primary-400 hover:text-primary-300 transition-colors">Edit</a>
                            <span class="text-dark-700 mx-2" aria-hidden="true">|</span>
                            <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="inline" onsubmit="return confirm('Delete this service?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-admin.card>
    <div class="mt-6">{{ $services->links() }}</div>
    @else
    <x-admin.card>
        <x-empty-state title="No services yet" description="Create your first service to get started." actionLabel="Create Service" actionUrl="{{ route('admin.services.create') }}" />
    </x-admin.card>
    @endif
</div>
@endsection