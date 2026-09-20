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

    <div class="grid sm:grid-cols-2 gap-4 mb-8">
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

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Account Actions</h2>
        <div class="space-y-3">
            @if($user->isActive())
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

            @if(auth()->id() !== $user->id)
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Permanently delete this user and all their data? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full rounded-lg bg-red-600 px-4 py-3 text-sm font-semibold text-white hover:bg-red-700 transition-colors flex items-center justify-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete User
                    </button>
                </form>
            @endif
            <div class="pt-4 border-t border-dark-800">
                <p class="text-xs text-dark-500">User since: {{ $user->created_at->format('M d, Y H:i') }}</p>
                <p class="text-xs text-dark-500">Last updated: {{ $user->updated_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6 mt-6" x-data="{ open: false }">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-white">Assign Number to User</h2>
            <button @click="open = !open" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors" x-text="open ? 'Cancel' : 'Assign Number'"></button>
        </div>
        <p class="text-xs text-dark-500 mt-1">The assigned number will appear in the user's "My Numbers" immediately.</p>
        <div x-show="open" x-cloak x-transition class="mt-4 pt-4 border-t border-dark-800">
            <form method="POST" action="{{ route('admin.users.assign-number', $user) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Available Number</label>
                    <select name="phone_number_id" required class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                        <option value="">Select a number...</option>
                        @foreach(\App\Models\PhoneNumber::where('status', 'available')->orderByDesc('created_at')->limit(100)->get() as $pn)
                            <option value="{{ $pn->id }}">{{ $pn->phone_number }} — {{ $pn->country }} ({{ number_format($pn->price, 2) }} {{ currency() }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors">Assign to {{ $user->name }}</button>
            </form>
        </div>
    </div>

    @if($user->numberPurchases->count())
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6 mt-6">
            <h2 class="text-lg font-semibold text-white mb-4">Purchased Numbers ({{ $user->numberPurchases->count() }})</h2>
            <div class="space-y-2">
                @foreach($user->numberPurchases as $purchase)
                    <div class="flex items-center justify-between rounded-lg bg-dark-800 border border-dark-700 px-4 py-3">
                        <div>
                            <p class="text-sm font-medium text-white">{{ $purchase->phoneNumber->phone_number ?? '—' }}</p>
                            <p class="text-xs text-dark-500">{{ $purchase->status instanceof \App\Enums\NumberPurchaseStatus ? $purchase->status->label() : $purchase->status }} · {{ $purchase->purchased_at?->format('M d, Y') }}</p>
                        </div>
                        <span class="text-sm text-dark-300">{{ number_format($purchase->price, 2) }} {{ currency() }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
