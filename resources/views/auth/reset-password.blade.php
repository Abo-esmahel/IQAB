@extends('layouts.auth', ['title' => 'Reset Password - IQAB'])

@section('content')
<div class="rounded-xl bg-dark-900 border border-dark-800 p-8">
    <h2 class="text-xl font-bold text-white text-center">Reset Password</h2>
    <p class="mt-2 text-sm text-dark-400 text-center">Choose a new strong password</p>

    <form method="POST" action="{{ route('password.store') }}" class="mt-8 space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label class="block text-sm font-medium text-dark-300 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email', request()->email) }}" required autofocus
                   class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors"
                   placeholder="you@example.com">
        </div>
        <div>
            <label class="block text-sm font-medium text-dark-300 mb-1.5">New Password</label>
            <input type="password" name="password" required
                   class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors"
                   placeholder="Min 8 characters, letters + numbers">
        </div>
        <div>
            <label class="block text-sm font-medium text-dark-300 mb-1.5">Confirm New Password</label>
            <input type="password" name="password_confirmation" required
                   class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors"
                   placeholder="Confirm new password">
        </div>
        <button type="submit" class="w-full rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">
            Reset Password
        </button>
    </form>
</div>
@endsection
