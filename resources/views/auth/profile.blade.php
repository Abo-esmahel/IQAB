@extends('layouts.app', ['title' => 'Profile - IQAB'])

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-white mb-6">Profile Settings</h1>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6 mb-6">
        <h2 class="text-lg font-semibold text-white mb-4">Personal Information</h2>
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none">
            </div>
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">Update Profile</button>
        </form>
    </div>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Change Password</h2>
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Current Password</label>
                <input type="password" name="current_password" required
                       class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">New Password</label>
                <input type="password" name="password" required
                       class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Confirm New Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none">
            </div>
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">Change Password</button>
        </form>
    </div>
</div>
@endsection