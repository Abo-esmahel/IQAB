@extends('layouts.auth', ['title' => 'Forgot Password - IQAB'])

@section('content')
<div class="rounded-xl bg-dark-900 border border-dark-800 p-8">
    <h2 class="text-xl font-bold text-white text-center">Forgot Password?</h2>
    <p class="mt-2 text-sm text-dark-400 text-center">Enter your email and we'll send you a reset link</p>

    @if(session('status'))
        <div class="mt-6 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3">
            <p class="text-sm text-emerald-300 text-center">{{ session('status') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-dark-300 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors"
                   placeholder="you@example.com">
        </div>
        <button type="submit" class="w-full rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">
            Send Reset Link
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-dark-500">
        Remembered your password?
        <a href="{{ route('login') }}" class="text-primary-400 hover:text-primary-300 font-medium">Login</a>
    </p>
</div>
@endsection
