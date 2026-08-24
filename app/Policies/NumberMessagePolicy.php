<?php

namespace App\Policies;

use App\Models\NumberMessage;
use App\Models\User;

class NumberMessagePolicy
{
    public function view(User $auth, NumberMessage $message): bool
    {
        return $message->user_id === $auth->id || $auth->isAdmin();
    }
}
