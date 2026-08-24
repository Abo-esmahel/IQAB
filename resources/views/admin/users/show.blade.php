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
            <div class="pt-4 border-t border-dark-800">
                <p class="text-xs text-dark-500">User since: {{ $user->created_at->format('M d, Y H:i') }}</p>
                <p class="text-xs text-dark-500">Last updated: {{ $user->updated_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
