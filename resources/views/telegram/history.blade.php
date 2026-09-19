@extends('layouts.app', ['title' => 'Telegram History - IQAB'])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-white">Telegram Service History</h1>
        <a href="{{ route('telegram.index') }}" class="text-sm text-primary-400 hover:text-primary-300">New Request</a>
    </div>

    @if($requests->count())
        <div class="rounded-xl bg-dark-900 border border-dark-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-dark-800">
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Service</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Target</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Status</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase hidden sm:table-cell">Date</th>
                        <th class="text-right py-3 px-4 text-xs font-medium text-dark-400 uppercase">Action</th>
                    </tr></thead>
                    <tbody>
                    @foreach($requests as $req)
                        <tr class="border-b border-dark-800/50 hover:bg-dark-800/50">
                            <td class="py-3 px-4 text-white">{{ $req->telegramService?->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-dark-300 font-mono text-xs">{{ $req->target_identifier }}</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    bg-{{ match($req->status->value) { 'completed' => 'emerald', 'processing' => 'amber', 'failed' => 'red', default => 'yellow' } }}-500/10
                                    text-{{ match($req->status->value) { 'completed' => 'emerald', 'processing' => 'amber', 'failed' => 'red', default => 'yellow' } }}-400">
                                    {{ $req->status->label() }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-dark-400 hidden sm:table-cell">{{ $req->created_at->format('M d, Y') }}</td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('telegram.result', $req) }}" class="text-primary-400 hover:text-primary-300 text-xs">View</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6">{{ $requests->links() }}</div>
    @else
        <x-empty-state title="No requests yet" description="Your Telegram service requests will appear here." actionLabel="Browse Services" actionUrl="{{ route('telegram.index') }}" />
    @endif
</div>
@endsection
