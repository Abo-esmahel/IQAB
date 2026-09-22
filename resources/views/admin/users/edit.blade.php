@extends('layouts.app', ['title' => 'Edit User - IQAB Admin'])

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-white">Edit User</h1>
        <a href="{{ route('admin.users.show', $user) }}" class="text-sm text-dark-400 hover:text-white transition-colors">&larr; Back to Profile</a>
    </div>

    <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Full Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors">
            </div>

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Email <span class="text-red-400">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">New Password</label>
                    <input type="password" name="password" placeholder="Leave blank to keep current"
                           class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Confirm New Password</label>
                    <input type="password" name="password_confirmation" placeholder="Repeat new password"
                           class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white placeholder-dark-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Role <span class="text-red-400">*</span></label>
                    <select name="role" required @if(auth()->id() === $user->id) disabled @endif class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors disabled:opacity-50">
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @if(auth()->id() === $user->id)
                        <input type="hidden" name="role" value="admin">
                        <p class="text-xs text-dark-500 mt-1">You cannot change your own role.</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Status <span class="text-red-400">*</span></label>
                    <select name="status" required @if(auth()->id() === $user->id) disabled @endif class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-colors disabled:opacity-50">
                        <option value="active" {{ old('status', $user->status->value ?? $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ old('status', $user->status->value ?? $user->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                    @if(auth()->id() === $user->id)
                        <input type="hidden" name="status" value="active">
                        <p class="text-xs text-dark-500 mt-1">You cannot suspend your own account.</p>
                    @endif
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-dark-800">
                <a href="{{ route('admin.users.show', $user) }}" class="rounded-lg border border-dark-700 px-4 py-2 text-sm font-medium text-dark-300 hover:bg-dark-800 transition-colors">Cancel</a>
                <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700 hover:shadow-[0_0_24px_rgba(216,156,43,0.25)] transition-all">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
