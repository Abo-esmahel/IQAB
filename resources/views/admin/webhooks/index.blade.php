@extends('layouts.app', ['title' => 'Webhook Logs - IQAB'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-white mb-6">Webhook Logs</h1>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-4 mb-6">
        <form method="GET" action="{{ route('admin.webhooks.index') }}" class="flex flex-col sm:flex-row gap-3">
            <select name="provider" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                <option value="">All Providers</option>
                <option value="phone" {{ request('provider') === 'phone' ? 'selected' : '' }}>Phone</option>
                <option value="telegram" {{ request('provider') === 'telegram' ? 'selected' : '' }}>Telegram</option>
            </select>
            <select name="status" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                <option value="">All Status</option>
                <option value="received" {{ request('status') === 'received' ? 'selected' : '' }}>Received</option>
                <option value="processed" {{ request('status') === 'processed' ? 'selected' : '' }}>Processed</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="ignored" {{ request('status') === 'ignored' ? 'selected' : '' }}>Ignored</option>
            </select>
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">Filter</button>
            @if(request()->hasAny(['provider', 'status']))
                <a href="{{ route('admin.webhooks.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 px-4 py-2 text-sm font-medium text-dark-300 hover:text-white transition-colors">Clear</a>
            @endif
        </form>
    </div>

    @if($logs->count())
        <div class="rounded-xl bg-dark-900 border border-dark-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-dark-800">
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">ID</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Provider</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Event</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Status</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase hidden sm:table-cell">Error</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase hidden sm:table-cell">Date</th>
                        <th class="text-right py-3 px-4 text-xs font-medium text-dark-400 uppercase">Actions</th>
                    </tr></thead>
                    <tbody>
                    @foreach($logs as $webhook)
                        <tr class="border-b border-dark-800/50 hover:bg-dark-800/50">
                            <td class="py-3 px-4 text-dark-300 font-mono text-xs">#{{ $webhook->id }}</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-dark-700 text-dark-300">
                                    {{ ucfirst($webhook->provider ?? 'N/A') }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-dark-200 font-mono text-xs">{{ $webhook->event ?? 'N/A' }}</td>
                            <td class="py-3 px-4">
                                @php
                                    $statusColor = match($webhook->status->value) {
                                        'received' => 'yellow',
                                        'processed' => 'emerald',
                                        'failed' => 'red',
                                        'ignored' => 'gray',
                                        default => 'gray',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $statusColor }}-500/10 text-{{ $statusColor }}-400">
                                    {{ $webhook->status->label() }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-red-400 text-xs max-w-xs truncate hidden sm:table-cell">{{ $webhook->error_message ?? '-' }}</td>
                            <td class="py-3 px-4 text-dark-500 hidden sm:table-cell">{{ $webhook->created_at->format('M d, Y H:i') }}</td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.webhooks.show', $webhook) }}" class="text-primary-400 hover:text-primary-300 text-sm font-medium">View</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6">{{ $logs->withQueryString()->links() }}</div>
    @else
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-12 text-center">
            <svg class="h-12 w-12 text-dark-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <p class="text-sm text-dark-500">No webhook logs found</p>
        </div>
    @endif
</div>
@endsection
