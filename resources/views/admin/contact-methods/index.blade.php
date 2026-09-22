@extends('layouts.app', ['title' => 'Contact Methods - IQAB Admin'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-white">Contact Methods</h1>
        <a href="{{ route('admin.contact-methods.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Contact Method
        </a>
    </div>

    @if($contactMethods->count())
    <div class="rounded-xl bg-dark-900 border border-dark-800 overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-dark-800">
                    <th class="text-left px-4 py-3 font-medium text-dark-400">Name</th>
                    <th class="text-left px-4 py-3 font-medium text-dark-400">Type</th>
                    <th class="text-left px-4 py-3 font-medium text-dark-400">Value</th>
                    <th class="text-left px-4 py-3 font-medium text-dark-400">Status</th>
                    <th class="text-left px-4 py-3 font-medium text-dark-400">Order</th>
                    <th class="text-left px-4 py-3 font-medium text-dark-400"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-800">
                @foreach($contactMethods as $method)
                <tr class="hover:bg-dark-800/50">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <x-brand-icon :type="$method->type" :image="$method->image" class="h-7 w-7" />
                            <span class="text-white font-medium">{{ $method->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-dark-700 text-dark-300 capitalize">{{ $method->type }}</span>
                    </td>
                    <td class="px-4 py-3 text-dark-300 font-mono text-xs">{{ $method->value }}</td>
                    <td class="px-4 py-3">
                        @if($method->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400">Active</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-dark-700 text-dark-400">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-dark-400">{{ $method->sort_order }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.contact-methods.edit', $method) }}" class="text-primary-400 hover:text-primary-300">Edit</a>
                        <form method="POST" action="{{ route('admin.contact-methods.destroy', $method) }}" class="inline" onsubmit="return confirm('Delete this contact method?')">
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
    @else
    <div class="rounded-xl bg-dark-900 border border-dark-800 p-12 text-center">
        <svg class="h-12 w-12 text-dark-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <p class="text-dark-400 mb-4">No contact methods yet</p>
        <a href="{{ route('admin.contact-methods.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
            Add First Contact Method
        </a>
    </div>
    @endif
</div>
@endsection
