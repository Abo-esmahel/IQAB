@extends('layouts.app', ['title' => 'Audit Logs - IQAB'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-white mb-6">Audit Logs</h1>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-4 mb-6">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="flex flex-col sm:flex-row gap-3">
            <select name="action" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                <option value="">All Actions</option>
                <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
                <option value="register" {{ request('action') === 'register' ? 'selected' : '' }}>Register</option>
                <option value="purchase" {{ request('action') === 'purchase' ? 'selected' : '' }}>Purchase</option>
                <option value="deposit" {{ request('action') === 'deposit' ? 'selected' : '' }}>Deposit</option>
                <option value="adjustment" {{ request('action') === 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                <option value="suspend" {{ request('action') === 'suspend' ? 'selected' : '' }}>Suspend</option>
                <option value="activate" {{ request('action') === 'activate' ? 'selected' : '' }}>Activate</option>
                <option value="webhook" {{ request('action') === 'webhook' ? 'selected' : '' }}>Webhook</option>
            </select>
            <select name="user_id" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
            <input type="date" name="from" value="{{ request('from') }}" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
            <input type="date" name="to" value="{{ request('to') }}" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">Filter</button>
            @if(request()->hasAny(['action', 'user_id', 'from', 'to']))
                <a href="{{ route('admin.audit-logs.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 px-4 py-2 text-sm font-medium text-dark-300 hover:text-white transition-colors">Clear</a>
            @endif
        </form>
    </div>

    @if($logs->count())
        <div class="rounded-xl bg-dark-900 border border-dark-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-dark-800">
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">ID</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">User</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Action</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Description</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase hidden sm:table-cell">IP</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase hidden sm:table-cell">Date</th>
                    </tr></thead>
                    <tbody>
                    @foreach($logs as $log)
                        <tr class="border-b border-dark-800/50 hover:bg-dark-800/50">
                            <td class="py-3 px-4 text-dark-300 font-mono text-xs">#{{ $log->id }}</td>
                            <td class="py-3 px-4">
                                <p class="text-sm text-dark-200">{{ $log->user?->name ?? 'System' }}</p>
                                <p class="text-xs text-dark-500">{{ $log->user?->email ?? '' }}</p>
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    $actionColor = match($log->action->value) {
                                        'login' => 'blue',
                                        'register' => 'emerald',
                                        'purchase' => 'purple',
                                        'deposit' => 'emerald',
                                        'adjustment' => 'amber',
                                        'suspend' => 'red',
                                        'activate' => 'green',
                                        'webhook' => 'sky',
                                        default => 'gray',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $actionColor }}-500/10 text-{{ $actionColor }}-400">
                                    {{ $log->action->label() }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-dark-300 max-w-xs truncate">{{ $log->description ?? '-' }}</td>
                            <td class="py-3 px-4 text-dark-500 font-mono text-xs hidden sm:table-cell">{{ $log->ip_address ?? '-' }}</td>
                            <td class="py-3 px-4 text-dark-500 hidden sm:table-cell">{{ $log->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6">{{ $logs->withQueryString()->links() }}</div>
    @else
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-12 text-center">
            <svg class="h-12 w-12 text-dark-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-sm text-dark-500">No audit logs found</p>
        </div>
    @endif
</div>
@endsection
