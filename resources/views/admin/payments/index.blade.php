@extends('layouts.app', ['title' => 'Payments - IQAB'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-white mb-6">Payments</h1>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-4 mb-6">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="flex flex-col sm:flex-row gap-3">
            <select name="status" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>
            <select name="provider" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                <option value="">All Providers</option>
                <option value="stripe" {{ request('provider') === 'stripe' ? 'selected' : '' }}>Stripe</option>
                <option value="paypal" {{ request('provider') === 'paypal' ? 'selected' : '' }}>PayPal</option>
                <option value="manual" {{ request('provider') === 'manual' ? 'selected' : '' }}>Manual</option>
            </select>
            <input type="date" name="from" value="{{ request('from') }}" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
            <input type="date" name="to" value="{{ request('to') }}" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">Filter</button>
            @if(request()->hasAny(['status', 'provider', 'from', 'to']))
                <a href="{{ route('admin.payments.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 px-4 py-2 text-sm font-medium text-dark-300 hover:text-white transition-colors">Clear</a>
            @endif
        </form>
    </div>

    @if($payments->count())
        <div class="rounded-xl bg-dark-900 border border-dark-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-dark-800">
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">ID</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">User</th>
                        <th class="text-right py-3 px-4 text-xs font-medium text-dark-400 uppercase">Amount</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Provider</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Status</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase hidden sm:table-cell">Date</th>
                        <th class="text-right py-3 px-4 text-xs font-medium text-dark-400 uppercase">Actions</th>
                    </tr></thead>
                    <tbody>
                    @foreach($payments as $payment)
                        <tr class="border-b border-dark-800/50 hover:bg-dark-800/50">
                            <td class="py-3 px-4 text-dark-300 font-mono text-xs">#{{ $payment->id }}</td>
                            <td class="py-3 px-4">
                                <p class="text-sm text-dark-200">{{ $payment->user?->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-dark-500">{{ $payment->user?->email ?? '' }}</p>
                            </td>
                            <td class="py-3 px-4 text-right text-white font-medium">{{ number_format($payment->amount, 2) }} SAR</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-dark-700 text-dark-300">
                                    {{ ucfirst($payment->provider ?? 'N/A') }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    $statusColor = match($payment->status->value) {
                                        'completed' => 'emerald',
                                        'pending' => 'yellow',
                                        'failed' => 'red',
                                        'refunded' => 'blue',
                                        default => 'gray',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $statusColor }}-500/10 text-{{ $statusColor }}-400">
                                    {{ $payment->status->label() }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-dark-500 hidden sm:table-cell">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                            <td class="py-3 px-4 text-right">
                                @if($payment->status->value === 'pending')
                                    <form method="POST" action="{{ route('admin.payments.approve', $payment) }}" class="inline">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Approve this payment?')" class="text-emerald-400 hover:text-emerald-300 text-sm font-medium">Approve</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6">{{ $payments->withQueryString()->links() }}</div>
    @else
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-12 text-center">
            <svg class="h-12 w-12 text-dark-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            <p class="text-sm text-dark-500">No payments found</p>
        </div>
    @endif
</div>
@endsection
