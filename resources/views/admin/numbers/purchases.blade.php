@extends('layouts.app', ['title' => 'All Purchases - IQAB'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-white mb-6">All Purchases</h1>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-4 mb-6">
        <form method="GET" action="{{ route('admin.numbers.purchases') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" placeholder="Search number or user..." value="{{ request('search') }}"
                   class="flex-1 rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
            <select name="status" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">Filter</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.numbers.purchases') }}" class="rounded-lg bg-dark-800 border border-dark-700 px-4 py-2 text-sm font-medium text-dark-300 hover:text-white transition-colors">Clear</a>
            @endif
        </form>
    </div>

    @if($purchases->count())
        <div class="rounded-xl bg-dark-900 border border-dark-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-dark-800">
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Phone Number</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">User</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Country</th>
                        <th class="text-right py-3 px-4 text-xs font-medium text-dark-400 uppercase">Amount</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Status</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase hidden sm:table-cell">Date</th>
                    </tr></thead>
                    <tbody>
                    @foreach($purchases as $purchase)
                        <tr class="border-b border-dark-800/50 hover:bg-dark-800/50">
                            <td class="py-3 px-4 text-white font-mono font-medium">{{ $purchase->phoneNumber?->phone_number ?? 'N/A' }}</td>
                            <td class="py-3 px-4">
                                <p class="text-sm text-dark-200">{{ $purchase->user?->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-dark-500">{{ $purchase->user?->email ?? '' }}</p>
                            </td>
                            <td class="py-3 px-4 text-dark-300">{{ $purchase->phoneNumber?->country ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-right text-dark-200">{{ number_format($purchase->price ?? 0, 2) }} SAR</td>
                            <td class="py-3 px-4">
                                @php
                                    $statusColor = match($purchase->status->value) {
                                        'active' => 'emerald',
                                        'expired' => 'red',
                                        'cancelled' => 'gray',
                                        default => 'yellow',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $statusColor }}-500/10 text-{{ $statusColor }}-400">
                                    {{ $purchase->status->label() }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-dark-500 hidden sm:table-cell">{{ $purchase->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6">{{ $purchases->withQueryString()->links() }}</div>
    @else
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-12 text-center">
            <svg class="h-12 w-12 text-dark-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-sm text-dark-500">No purchases found</p>
        </div>
    @endif
</div>
@endsection
