@extends('layouts.app', ['title' => 'Wallet - IQAB'])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-white mb-6">Wallet</h1>

    <div class="grid sm:grid-cols-3 gap-4 mb-8">
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <p class="text-sm text-dark-400">Current Balance</p>
            <p class="text-3xl font-bold text-white mt-1">{{ number_format($wallet->balance, 2) }} <span class="text-base text-dark-400">SAR</span></p>
        </div>
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <p class="text-sm text-dark-400">Total Deposited</p>
            <p class="text-3xl font-bold text-emerald-400 mt-1">{{ number_format($wallet->total_deposited, 2) }} <span class="text-base text-dark-400">SAR</span></p>
        </div>
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <p class="text-sm text-dark-400">Total Spent</p>
            <p class="text-3xl font-bold text-red-400 mt-1">{{ number_format($wallet->total_spent, 2) }} <span class="text-base text-dark-400">SAR</span></p>
        </div>
    </div>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6 mb-6">
        <h2 class="text-lg font-semibold text-white mb-4">Add Balance</h2>
        <form method="POST" action="{{ route('wallet.deposit') }}" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <input type="number" name="amount" min="1" max="10000" step="0.01" placeholder="Amount (SAR)" required
                   class="flex-1 rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none">
            <button type="submit" class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors">Deposit</button>
        </form>
        <p class="text-xs text-dark-500 mt-2">Payment gateway integration is coming soon. Contact support to add balance.</p>
    </div>

    <div class="rounded-xl bg-dark-900 border border-dark-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-dark-800">
            <h2 class="text-lg font-semibold text-white">Recent Transactions</h2>
        </div>
        @if($transactions->count())
            <div class="divide-y divide-dark-800">
                @foreach($transactions as $tx)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-dark-200">{{ $tx->description ?? $tx->type->label() }}</p>
                        <p class="text-xs text-dark-500">{{ $tx->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="text-right shrink-0 ml-4">
                        <p class="text-sm font-semibold {{ in_array($tx->type->value, ['deposit', 'refund', 'adjustment']) && $tx->amount > 0 ? 'text-emerald-400' : 'text-red-400' }}">
                            {{ in_array($tx->type->value, ['deposit', 'refund']) || ($tx->type->value === 'adjustment' && $tx->amount > 0) ? '+' : '-' }}{{ number_format(abs($tx->amount), 2) }} SAR
                        </p>
                        <p class="text-xs text-dark-500">{{ $tx->status->label() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="px-6 py-4 border-t border-dark-800">{{ $transactions->links() }}</div>
        @else
            <div class="px-6 py-12 text-center">
                <p class="text-sm text-dark-500">No transactions yet</p>
            </div>
        @endif
    </div>
</div>
@endsection
