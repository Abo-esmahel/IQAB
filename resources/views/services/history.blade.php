@extends('layouts.app', ['title' => 'Service History - IQAB'])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-white">Service History</h1>
        <a href="{{ route('services.index') }}" class="text-sm text-primary-400 hover:text-primary-300">Browse Services</a>
    </div>

    @if($purchases->count())
    <div class="rounded-xl bg-dark-900 border border-dark-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-dark-800">
                        <th class="text-left px-4 py-3 font-medium text-dark-400">Service</th>
                        <th class="text-left px-4 py-3 font-medium text-dark-400">Price</th>
                        <th class="text-left px-4 py-3 font-medium text-dark-400">Status</th>
                        <th class="text-left px-4 py-3 font-medium text-dark-400">Date</th>
                        <th class="text-left px-4 py-3 font-medium text-dark-400"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-800">
                    @foreach($purchases as $purchase)
                    <tr class="hover:bg-dark-800/50">
                        <td class="px-4 py-3 text-white">{{ $purchase->marketService?->name }}</td>
                        <td class="px-4 py-3 text-dark-300">{{ number_format($purchase->price, 2) }} SAR</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                bg-{{ $purchase->status === 'completed' ? 'emerald' : ($purchase->status === 'failed' ? 'red' : 'yellow') }}-500/10
                                text-{{ $purchase->status === 'completed' ? 'emerald' : ($purchase->status === 'failed' ? 'red' : 'yellow') }}-400">
                                {{ ucfirst($purchase->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-dark-400">{{ $purchase->created_at->format('M d, H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('services.result', $purchase) }}" class="text-primary-400 hover:text-primary-300">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">{{ $purchases->links() }}</div>
    @else
    <x-empty-state title="No service purchases" description="You haven't purchased any services yet." />
    @endif
</div>
@endsection
