<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wallet;

class WalletPolicy
{
    public function view(User $auth, Wallet $wallet): bool
    {
        return $wallet->user_id === $auth->id || $auth->isAdmin();
    }

    public function manage(User $auth, Wallet $wallet): bool
    {
        return $wallet->user_id === $auth->id;
    }
}
