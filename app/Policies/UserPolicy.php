<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $auth): bool
    {
        return $auth->isAdmin();
    }

    public function view(User $auth, User $user): bool
    {
        return $auth->id === $user->id || $auth->isAdmin();
    }

    public function update(User $auth, User $user): bool
    {
        return $auth->id === $user->id || $auth->isAdmin();
    }

    public function suspend(User $auth, User $user): bool
    {
        return $auth->isAdmin() && $auth->id !== $user->id;
    }

    public function activate(User $auth, User $user): bool
    {
        return $auth->isAdmin() && $auth->id !== $user->id;
    }

    public function adjustBalance(User $auth, User $user): bool
    {
        return $auth->isAdmin();
    }

    public function delete(User $auth, User $user): bool
    {
        return $auth->isAdmin() && $auth->id !== $user->id;
    }
}
