@extends('layouts.app', ['title' => 'Admin Dashboard - IQAB'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Admin Dashboard</h1>
            <p class="text-sm text-dark-400 mt-1">Live overview of users, revenue and operations.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add User
        </a>
    </div>

    {{-- KPI cards --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stat-card title="Total Revenue" value="{{ number_format($stats['total_revenue'], 2) }}" subtitle="Numbers {{ number_format($stats['numbers_revenue'], 0) }} · Services {{ number_format($stats['services_revenue'], 0) }} · TG {{ number_format($stats['telegram_revenue'], 0) }} {{ currency() }}" color="emerald" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>' />
        <x-stat-card title="Total Users" value="{{ number_format($stats['total_users']) }}" subtitle="{{ number_format($stats['active_users']) }} active · {{ number_format($stats['admins']) }} admins · +{{ number_format($stats['new_users_7d']) }} this week" color="primary" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>' />
        <x-stat-card title="Purchases" value="{{ number_format($stats['total_purchases']) }}" subtitle="{{ number_format($stats['active_numbers']) }} active numbers · {{ number_format($stats['available_numbers']) }} in stock" color="primary" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>' />
        <x-stat-card title="Telegram Requests" value="{{ number_format($stats['telegram_total']) }}" subtitle="{{ number_format($stats['telegram_processing']) }} processing · {{ number_format($stats['telegram_failed']) }} failed" color="amber" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>' />
    </div>

    <div class="grid lg:grid-cols-3 gap-6 mb-6">
        {{-- Registrations chart --}}
        <div class="lg:col-span-2 rounded-xl bg-dark-900 border border-dark-800 p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-semibold text-white">New Registrations <span class="text-xs font-normal text-dark-500">last 30 days</span></h2>
                <span class="text-xs text-dark-500">Total {{ array_sum(array_column($chart, 'count')) }}</span>
            </div>
            <div class="flex items-end gap-1 h-36" title="Daily registrations">
                @foreach($chart as $bar)
                <div class="flex-1 flex flex-col items-center justify-end gap-1 h-full group relative">
                    <span class="absolute -top-1 hidden group-hover:block text-[10px] text-primary-300 bg-dark-800 border border-dark-700 rounded px-1.5 py-0.5 whitespace-nowrap z-10">{{ $bar['label'] }}: {{ $bar['count'] }}</span>
                    <div class="w-full max-w-5 rounded-t {{ $bar['count'] > 0 ? 'bg-gradient-to-t from-primary-700 to-primary-400' : 'bg-dark-800' }}" style="height: {{ max(4, round($bar['count'] / $maxChart * 100)) }}%"></div>
                </div>
                @endforeach
            </div>
            <div class="flex justify-between mt-2 text-[10px] text-dark-600">
                <span>{{ $chart[0]['label'] ?? '' }}</span><span>{{ $chart[14]['label'] ?? '' }}</span><span>{{ $chart[29]['label'] ?? 'Today' }}</span>
            </div>
        </div>

        {{-- Top countries --}}
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Top Countries</h2>
            @forelse($topCountries as $row)
                <div class="mb-4 last:mb-0">
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="text-dark-200 font-medium">{{ $row->country ?? 'Unknown' }}</span>
                        <span class="text-xs text-dark-500">{{ $row->total }} sold · {{ number_format($row->revenue, 0) }} {{ currency() }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-dark-800 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-primary-700 to-primary-400" style="width: {{ $topCountries->first() && $topCountries->first()->total ? round($row->total / $topCountries->first()->total * 100) : 0 }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-dark-500 text-center py-4">No sales yet</p>
            @endforelse
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Quick Links --}}
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Quick Links</h2>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.users.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-4 text-center hover:border-primary-500/50 transition-colors">
                    <svg class="h-6 w-6 text-primary-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="text-xs font-medium text-dark-300">Users</span>
                </a>
                <a href="{{ route('admin.numbers.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-4 text-center hover:border-primary-500/50 transition-colors">
                    <svg class="h-6 w-6 text-primary-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span class="text-xs font-medium text-dark-300">Numbers</span>
                </a>
                <a href="{{ route('admin.services.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-4 text-center hover:border-primary-500/50 transition-colors">
                    <svg class="h-6 w-6 text-primary-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span class="text-xs font-medium text-dark-300">Services</span>
                </a>
                <a href="{{ route('admin.offers.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-4 text-center hover:border-primary-500/50 transition-colors">
                    <svg class="h-6 w-6 text-primary-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5.586a1 1 0 01.707.293l7.414 7.414a1 1 0 010 1.414l-5.586 5.586a1 1 0 01-1.414 0L5.293 10.293A1 1 0 015 9.586V4a1 1 0 011-1z"/></svg>
                    <span class="text-xs font-medium text-dark-300">Offers</span>
                </a>
                <a href="{{ route('admin.contact-methods.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-4 text-center hover:border-primary-500/50 transition-colors">
                    <svg class="h-6 w-6 text-primary-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span class="text-xs font-medium text-dark-300">Contact</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 p-4 text-center hover:border-primary-500/50 transition-colors">
                    <svg class="h-6 w-6 text-primary-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="text-xs font-medium text-dark-300">Settings</span>
                </a>
            </div>
        </div>

        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
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
                        {{ $user->role === 'admin' ? 'Admin' : ($user->status->value === 'active' ? 'Active' : 'Suspended') }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-dark-500 text-center py-4">No users yet</p>
            @endforelse
        </div>

        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">Recent Purchases</h2>
                <a href="{{ route('admin.numbers.purchases') }}" class="text-xs text-primary-400 hover:text-primary-300">View All</a>
            </div>
            @forelse($recentPurchases as $purchase)
                <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-dark-800' : '' }}">
                    <div class="min-w-0">
                        <p class="text-sm text-dark-200 truncate font-mono">{{ $purchase->phoneNumber?->phone_number ?? 'N/A' }}</p>
                        <p class="text-xs text-dark-500">{{ $purchase->user?->name ?? 'Unknown' }} · {{ number_format($purchase->price, 2) }} {{ currency() }}</p>
                    </div>
                    <x-status-pill :status="$purchase->status->value" :label="$purchase->status->label()" />
                </div>
            @empty
                <p class="text-sm text-dark-500 text-center py-4">No purchases yet</p>
            @endforelse
        </div>
    </div>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">Recent Telegram Requests</h2>
            <a href="{{ route('admin.telegram.bot.index') }}" class="text-xs text-primary-400 hover:text-primary-300">Bot Panel</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b border-dark-800">
                    <th class="text-left py-2 px-3 text-xs font-medium text-dark-400 uppercase">User</th>
                    <th class="text-left py-2 px-3 text-xs font-medium text-dark-400 uppercase">Service</th>
                    <th class="text-left py-2 px-3 text-xs font-medium text-dark-400 uppercase">Target</th>
                    <th class="text-left py-2 px-3 text-xs font-medium text-dark-400 uppercase">Status</th>
                    <th class="text-right py-2 px-3 text-xs font-medium text-dark-400 uppercase">Date</th>
                </tr></thead>
                <tbody>
                @forelse($recentTelegram as $req)
                    <tr class="border-b border-dark-800/50 last:border-0">
                        <td class="py-2.5 px-3 text-dark-200">{{ $req->user?->name ?? '—' }}</td>
                        <td class="py-2.5 px-3 text-dark-300">{{ $req->telegramService?->name ?? 'N/A' }}</td>
                        <td class="py-2.5 px-3 text-dark-400 font-mono text-xs">{{ $req->target_identifier }}</td>
                        <td class="py-2.5 px-3"><x-status-pill :status="$req->status" :label="$req->status->label()" /></td>
                        <td class="py-2.5 px-3 text-right text-xs text-dark-500">{{ $req->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-4 text-sm text-dark-500 text-center">No telegram requests yet</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
