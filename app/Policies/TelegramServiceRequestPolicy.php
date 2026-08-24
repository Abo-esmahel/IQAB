<?php

namespace App\Policies;

use App\Models\TelegramServiceRequest;
use App\Models\User;

class TelegramServiceRequestPolicy
{
    public function view(User $auth, TelegramServiceRequest $request): bool
    {
        return $request->user_id === $auth->id || $auth->isAdmin();
    }
}
