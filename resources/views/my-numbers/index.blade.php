@extends('layouts.app', ['title' => 'My Numbers - IQAB'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-white">My Numbers</h1>
        <a href="{{ route('numbers.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buy Number
        </a>
    </div>

    @if($purchases->count())
        <div class="rounded-xl bg-dark-900 border border-dark-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-dark-800">
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Number</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Country</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Status</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase hidden sm:table-cell">Purchased</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase hidden sm:table-cell">Expires</th>
                        <th class="text-right py-3 px-4 text-xs font-medium text-dark-400 uppercase">Actions</th>
                    </tr></thead>
                    <tbody>
                    @foreach($purchases as $purchase)
                        <tr class="border-b border-dark-800/50 hover:bg-dark-800/50">
                            <td class="py-3 px-4">
                                <span class="text-white font-mono font-medium">{{ $purchase->phoneNumber?->phone_number ?? 'N/A' }}</span>
                                @if($purchase->label)
                                    <span class="block text-xs text-primary-400">{{ $purchase->label }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-dark-300">{{ $purchase->phoneNumber?->country ?? 'N/A' }}</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    bg-{{ $purchase->status->value === 'active' ? 'emerald' : ($purchase->status->value === 'expired' ? 'red' : ($purchase->status->value === 'cancelled' ? 'dark' : 'yellow')) }}-500/10
                                    text-{{ $purchase->status->value === 'active' ? 'emerald' : ($purchase->status->value === 'expired' ? 'red' : ($purchase->status->value === 'cancelled' ? 'dark-300' : 'yellow')) }}">
                                    {{ $purchase->status->label() }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-dark-400 hidden sm:table-cell">{{ $purchase->purchased_at?->format('M d, Y') ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-dark-400 hidden sm:table-cell">{{ $purchase->expires_at?->format('M d, Y') ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('my-numbers.show', $purchase) }}" class="rounded-lg bg-dark-800 px-3 py-1.5 text-xs font-medium text-dark-300 hover:text-white transition-colors">View</a>
                                    @if($purchase->status->value === 'active')
                                        <a href="{{ route('inbox.index', $purchase) }}" class="rounded-lg bg-primary-600/10 px-3 py-1.5 text-xs font-medium text-primary-400 hover:bg-primary-600/20 transition-colors">Inbox</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6">{{ $purchases->links() }}</div>
    @else
        <x-empty-state title="No numbers yet" description="Purchase your first virtual number to get started." actionLabel="Browse Numbers" actionUrl="{{ route('numbers.index') }}" />
    @endif
</div>
@endsection
