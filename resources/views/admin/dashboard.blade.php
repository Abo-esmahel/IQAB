@extends('layouts.app', ['title' => 'Admin Dashboard - IQAB'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-white mb-6">Admin Dashboard</h1>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-stat-card title="Total Users" value="{{ number_format($stats['total_users']) }}" color="blue" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>' />
        <x-stat-card title="Active Users" value="{{ number_format($stats['active_users']) }}" color="emerald" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>' />
        <x-stat-card title="Active Numbers" value="{{ number_format($stats['active_numbers']) }}" color="purple" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>' />
        <x-stat-card title="Total Revenue" value="{{ number_format($stats['total_revenue'], 2) }} SAR" color="amber" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>' />
    </div>

    <div class="grid lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-1 rounded-xl bg-dark-900 border border-dark-800 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Quick Links</h2>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.users.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-4 text-center hover:border-primary-500/50 transition-colors">
                    <svg class="h-6 w-6 text-primary-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="text-xs font-medium text-dark-300">Users</span>
                </a>
                <a href="{{ route('admin.numbers.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-4 text-center hover:border-primary-500/50 transition-colors">
                    <svg class="h-6 w-6 text-purple-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span class="text-xs font-medium text-dark-300">Numbers</span>
                </a>
                <a href="{{ route('admin.payments.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-4 text-center hover:border-primary-500/50 transition-colors">
                    <svg class="h-6 w-6 text-emerald-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span class="text-xs font-medium text-dark-300">Payments</span>
                </a>
                <a href="{{ route('admin.webhooks.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-4 text-center hover:border-primary-500/50 transition-colors">
                    <svg class="h-6 w-6 text-sky-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span class="text-xs font-medium text-dark-300">Webhooks</span>
                </a>
                <a href="{{ route('admin.audit-logs.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-4 text-center hover:border-primary-500/50 transition-colors">
                    <svg class="h-6 w-6 text-amber-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span class="text-xs font-medium text-dark-300">Audit Logs</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-4 text-center hover:border-primary-500/50 transition-colors">
                    <svg class="h-6 w-6 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="text-xs font-medium text-dark-300">Settings</span>
                </a>
                <a href="{{ route('admin.telegram.bot.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-4 text-center hover:border-primary-500/50 transition-colors">
                    <svg class="h-6 w-6 text-sky-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span class="text-xs font-medium text-dark-300">Telegram Bot</span>
                </a>
                <a href="{{ route('admin.contact-methods.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-4 text-center hover:border-primary-500/50 transition-colors">
                    <svg class="h-6 w-6 text-pink-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span class="text-xs font-medium text-dark-300">Contact Methods</span>
                </a>
            </div>
        </div>

        <div class="lg:col-span-1 rounded-xl bg-dark-900 border border-dark-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">Recent Users</h2>
                <a href="{{ route('admin.users.index') }}" class="text-xs text-primary-400 hover:text-primary-300">View All</a>
            </div>
            @forelse($recentUsers as $user)
                <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-dark-800' : '' }}">
                    <div class="min-w-0">
                        <p class="text-sm text-dark-200 truncate">{{ $user->name }}</p>
                        <p class="text-xs text-dark-500 truncate">{{ $user->email }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $user->status->value === 'active' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }}">
                        {{ $user->status->value === 'active' ? 'Active' : ucfirst($user->status->value) }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-dark-500 text-center py-4">No users yet</p>
            @endforelse
        </div>

        <div class="lg:col-span-1 rounded-xl bg-dark-900 border border-dark-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">Recent Purchases</h2>
                <a href="{{ route('admin.numbers.purchases') }}" class="text-xs text-primary-400 hover:text-primary-300">View All</a>
            </div>
            @forelse($recentPurchases as $purchase)
                <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-dark-800' : '' }}">
                    <div class="min-w-0">
                        <p class="text-sm text-dark-200 truncate font-mono">{{ $purchase->phoneNumber?->phone_number ?? 'N/A' }}</p>
                        <p class="text-xs text-dark-500">{{ $purchase->user?->name ?? 'Unknown' }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $purchase->status->value === 'active' ? 'emerald' : ($purchase->status->value === 'expired' ? 'red' : 'yellow') }}-500/10 text-{{ $purchase->status->value === 'active' ? 'emerald' : ($purchase->status->value === 'expired' ? 'red' : 'yellow') }}-400">
                        {{ $purchase->status->label() }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-dark-500 text-center py-4">No purchases yet</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
