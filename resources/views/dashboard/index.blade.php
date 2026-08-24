@extends('layouts.app', ['title' => 'Dashboard - IQAB'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-white mb-6">Dashboard</h1>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-stat-card title="Balance" value="{{ number_format($balance, 2) }} SAR" color="emerald" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>' />
        <x-stat-card title="Active Numbers" value="{{ $activeNumbers }}" color="blue" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>' />
        <x-stat-card title="Total Numbers" value="{{ $totalNumbers }}" color="purple" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>' />
        <x-stat-card title="Messages" value="{{ $totalMessages }}" color="amber" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>' />
    </div>

    <div class="grid lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-1 rounded-xl bg-dark-900 border border-dark-800 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('numbers.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-4 text-center hover:border-primary-500/50 transition-colors">
                    <svg class="h-6 w-6 text-primary-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs font-medium text-dark-300">Buy Number</span>
                </a>
                <a href="{{ route('my-numbers.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-4 text-center hover:border-primary-500/50 transition-colors">
                    <svg class="h-6 w-6 text-primary-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span class="text-xs font-medium text-dark-300">My Numbers</span>
                </a>
                <a href="{{ route('wallet.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-4 text-center hover:border-primary-500/50 transition-colors">
                    <svg class="h-6 w-6 text-emerald-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span class="text-xs font-medium text-dark-300">Wallet</span>
                </a>
                <a href="{{ route('telegram.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-4 text-center hover:border-primary-500/50 transition-colors">
                    <svg class="h-6 w-6 text-sky-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span class="text-xs font-medium text-dark-300">Telegram</span>
                </a>
            </div>
        </div>

        <div class="lg:col-span-1 rounded-xl bg-dark-900 border border-dark-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">Recent Transactions</h2>
                <a href="{{ route('transactions.index') }}" class="text-xs text-primary-400 hover:text-primary-300">View All</a>
            </div>
            @forelse($recentTransactions as $tx)
                <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-dark-800' : '' }}">
                    <div>
                        <p class="text-sm text-dark-200">{{ $tx->description ?? $tx->type->label() }}</p>
                        <p class="text-xs text-dark-500">{{ $tx->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="text-sm font-medium {{ $tx->type->value === 'deposit' || $tx->type->value === 'refund' ? 'text-emerald-400' : 'text-red-400' }}">
                        {{ $tx->type->value === 'deposit' || $tx->type->value === 'refund' ? '+' : '-' }}{{ number_format($tx->amount, 2) }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-dark-500 text-center py-4">No transactions yet</p>
            @endforelse
        </div>

        <div class="lg:col-span-1 rounded-xl bg-dark-900 border border-dark-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">Recent Messages</h2>
            </div>
            @forelse($recentMessages as $msg)
                <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-dark-800' : '' }}">
                    <div class="min-w-0">
                        <p class="text-sm text-dark-200 truncate">{{ $msg->sender ?? 'Unknown' }}</p>
                        <p class="text-xs text-dark-500 truncate">{{ $msg->message }}</p>
                    </div>
                    <span class="text-xs text-dark-500 shrink-0 ml-2">{{ $msg->received_at?->diffForHumans() ?? '' }}</span>
                </div>
            @empty
                <p class="text-sm text-dark-500 text-center py-4">No messages yet</p>
            @endforelse
        </div>
    </div>

    @if($recentPurchases->count())
    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">Recent Purchases</h2>
            <a href="{{ route('my-numbers.index') }}" class="text-xs text-primary-400 hover:text-primary-300">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b border-dark-800">
                    <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Number</th>
                    <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Country</th>
                    <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Status</th>
                    <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Date</th>
                </tr></thead>
                <tbody>
                @foreach($recentPurchases as $purchase)
                    <tr class="border-b border-dark-800/50 hover:bg-dark-800/50">
                        <td class="py-3 px-4 text-white font-mono">{{ $purchase->phoneNumber?->phone_number ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-dark-300">{{ $purchase->phoneNumber?->country ?? 'N/A' }}</td>
                        <td class="py-3 px-4"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $purchase->status->value === 'active' ? 'emerald' : ($purchase->status->value === 'expired' ? 'red' : 'yellow') }}-500/10 text-{{ $purchase->status->value === 'active' ? 'emerald' : ($purchase->status->value === 'expired' ? 'red' : 'yellow') }}-400">{{ $purchase->status->label() }}</span></td>
                        <td class="py-3 px-4 text-dark-400">{{ $purchase->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
