@extends('layouts.app', ['title' => 'All Numbers - IQAB'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-white">Phone Numbers</h1>
        <a href="{{ route('admin.numbers.create') }}" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
            + Publish Number
        </a>
    </div>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-4 mb-6">
        <form method="GET" action="{{ route('admin.numbers.index') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" placeholder="Search number..." value="{{ request('search') }}"
                   class="flex-1 rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none">
            <select name="status" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                <option value="">All Status</option>
                @foreach(\App\Enums\PhoneNumberStatus::cases() as $case)
                    <option value="{{ $case->value }}" {{ request('status') === $case->value ? 'selected' : '' }}>{{ $case->label() }}</option>
                @endforeach
            </select>
            <select name="country" class="rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                <option value="">All Countries</option>
                @foreach($countries as $country)
                    <option value="{{ $country }}" {{ request('country') === $country ? 'selected' : '' }}>{{ $country }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">Filter</button>
            @if(request()->hasAny(['search', 'status', 'country']))
                <a href="{{ route('admin.numbers.index') }}" class="rounded-lg bg-dark-800 border border-dark-700 px-4 py-2 text-sm font-medium text-dark-300 hover:text-white transition-colors">Clear</a>
            @endif
        </form>
    </div>

    @if($numbers->count())
        <div class="rounded-xl bg-dark-900 border border-dark-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-dark-800">
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Phone Number</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Country</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Price</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase">Status</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-dark-400 uppercase hidden sm:table-cell">Expires</th>
                        <th class="text-right py-3 px-4 text-xs font-medium text-dark-400 uppercase">Actions</th>
                    </tr></thead>
                    <tbody>
                    @foreach($numbers as $number)
                        <tr class="border-b border-dark-800/50 hover:bg-dark-800/50">
                            <td class="py-3 px-4 text-white font-mono font-medium">{{ $number->phone_number }}</td>
                            <td class="py-3 px-4 text-dark-300">{{ $number->country }}</td>
                            <td class="py-3 px-4 text-dark-200">{{ number_format($number->price, 2) }} SAR</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $number->status->color() }}-500/10 text-{{ $number->status->color() }}-400">
                                    {{ $number->status->label() }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-dark-500 hidden sm:table-cell">{{ $number->expires_at ? $number->expires_at->format('M d, Y') : '-' }}</td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.numbers.edit', $number) }}" class="text-sm font-medium text-primary-400 hover:text-primary-300">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6">{{ $numbers->links() }}</div>
    @else
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-12 text-center">
            <svg class="h-12 w-12 text-dark-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            <p class="text-sm text-dark-500">No numbers found</p>
        </div>
    @endif
</div>
@endsection
