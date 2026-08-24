@extends('layouts.auth', ['title' => 'Login - IQAB'])

@section('content')
<div class="rounded-xl bg-dark-900 border border-dark-800 p-8">
    <h2 class="text-xl font-bold text-white text-center">Welcome Back</h2>
    <p class="mt-2 text-sm text-dark-400 text-center">Sign in to your account</p>

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-dark-300 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors"
                   placeholder="you@example.com">
        </div>
        <div>
            <label class="block text-sm font-medium text-dark-300 mb-1.5">Password</label>
            <input type="password" name="password" required
                   class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors"
                   placeholder="Password">
        </div>
        <button type="submit" class="w-full rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">
            Sign In
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-dark-500">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-primary-400 hover:text-primary-300 font-medium">Register</a>
    </p>
</div>
@endsection