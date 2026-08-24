@extends('layouts.app', ['title' => 'User Detail - IQAB'])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.users.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-2 text-dark-400 hover:text-white transition-colors">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
            <p class="text-sm text-dark-400">{{ $user->email }}</p>
        </div>
    </div>

    <div class="grid sm:grid-cols-3 gap-4 mb-8">
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <p class="text-sm text-dark-400">Balance</p>
            <p class="text-2xl font-bold text-white mt-1">{{ number_format($user->wallet->balance ?? 0, 2) }} SAR</p>
        </div>
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <p class="text-sm text-dark-400">Role</p>
            <p class="text-2xl font-bold text-white mt-1">{{ ucfirst($user->role) }}</p>
        </div>
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <p class="text-sm text-dark-400">Status</p>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1 {{ $user->status->value === 'active' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }}">
                {{ $user->status->value === 'active' ? 'Active' : ucfirst($user->status->value) }}
            </span>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6 mb-8">
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Adjust Balance</h2>
            <form method="POST" action="{{ route('admin.users.adjust-balance', $user) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1">Type</label>
                    <select name="type" class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                        <option value="deposit">Deposit (Add)</option>
                        <option value="deduction">Deduction (Subtract)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1">Amount (SAR)</label>
                    <input type="number" name="amount" min="0.01" step="0.01" required
                           class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1">Reason</label>
                    <input type="text" name="description" required
                            class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none" placeholder="e.g. Manual credit">
                </div>
                <button type="submit" onclick="return confirm('Adjust this user balance?')" class="w-full rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">
                    Apply Adjustment
                </button>
            </form>
        </div>

        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Account Actions</h2>
            <div class="space-y-3">
                @if($user->is_active)
                    <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                        @csrf
                        <button type="submit" onclick="return confirm('Suspend this user?')" class="w-full rounded-lg bg-red-600/10 border border-red-500/20 px-4 py-3 text-sm font-semibold text-red-400 hover:bg-red-600/20 transition-colors flex items-center justify-center gap-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            Suspend User
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.users.activate', $user) }}">
                        @csrf
                        <button type="submit" onclick="return confirm('Activate this user?')" class="w-full rounded-lg bg-emerald-600/10 border border-emerald-500/20 px-4 py-3 text-sm font-semibold text-emerald-400 hover:bg-emerald-600/20 transition-colors flex items-center justify-center gap-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Activate User
                        </button>
                    </form>
                @endif
                <div class="pt-4 border-t border-dark-800">
                    <p class="text-xs text-dark-500">User since: {{ $user->created_at->format('M d, Y H:i') }}</p>
                    <p class="text-xs text-dark-500">Last updated: {{ $user->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-xl bg-dark-900 border border-dark-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-dark-800">
            <h2 class="text-lg font-semibold text-white">Recent Transactions</h2>
        </div>
        @if($transactions->count())
            <div class="divide-y divide-dark-800">
                @foreach($transactions->take(10) as $tx)
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
        @else
            <div class="px-6 py-12 text-center">
                <p class="text-sm text-dark-500">No transactions yet</p>
            </div>
        @endif
    </div>
</div>
@endsection
