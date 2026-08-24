@extends('layouts.app', ['title' => 'Transactions - IQAB'])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-white mb-6">Transaction History</h1>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-4 mb-6">
        <form method="GET" action="{{ route('transactions.index') }}" class="flex flex-col sm:flex-row gap-3">
            <select name="type" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                <option value="">All Types</option>
                <option value="deposit" {{ request('type') === 'deposit' ? 'selected' : '' }}>Deposit</option>
                <option value="purchase" {{ request('type') === 'purchase' ? 'selected' : '' }}>Purchase</option>
                <option value="refund" {{ request('type') === 'refund' ? 'selected' : '' }}>Refund</option>
                <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                <option value="service_charge" {{ request('type') === 'service_charge' ? 'selected' : '' }}>Service Charge</option>
            </select>
            <select name="status" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                <option value="">All Statuses</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <input type="date" name="from" value="{{ request('from') }}" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
            <input type="date" name="to" value="{{ request('to') }}" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">Filter</button>
        </form>
    </div>

    @if($transactions->count())
        <div class="rounded-xl bg-dark-900 border border-dark-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-dark-800">
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Type</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Description</th>
                        <th class="text-right py-3 px-4 text-xs font-medium text-dark-400 uppercase">Amount</th>
                        <th class="text-right py-3 px-4 text-xs font-medium text-dark-400 uppercase">Balance</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase hidden sm:table-cell">Date</th>
                    </tr></thead>
                    <tbody>
                    @foreach($transactions as $tx)
                        <tr class="border-b border-dark-800/50 hover:bg-dark-800/50">
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    bg-{{ match($tx->type->value) { 'deposit' => 'emerald', 'refund' => 'blue', 'purchase' => 'red', 'service_charge' => 'amber', default => 'gray' } }}-500/10
                                    text-{{ match($tx->type->value) { 'deposit' => 'emerald', 'refund' => 'blue', 'purchase' => 'red', 'service_charge' => 'amber', default => 'gray' } }}-400">
                                    {{ $tx->type->label() }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-dark-300 max-w-xs truncate">{{ $tx->description ?? '-' }}</td>
                            <td class="py-3 px-4 text-right font-medium {{ in_array($tx->type->value, ['deposit', 'refund']) || ($tx->type->value === 'adjustment' && $tx->amount > 0) ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ in_array($tx->type->value, ['deposit', 'refund']) || ($tx->type->value === 'adjustment' && $tx->amount > 0) ? '+' : '-' }}{{ number_format(abs($tx->amount), 2) }}
                            </td>
                            <td class="py-3 px-4 text-right text-dark-400">{{ number_format($tx->balance_after, 2) }}</td>
                            <td class="py-3 px-4 text-dark-500 hidden sm:table-cell">{{ $tx->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6">{{ $transactions->withQueryString()->links() }}</div>
    @else
        <x-empty-state title="No transactions" description="Your transaction history will appear here." />
    @endif
</div>
@endsection
