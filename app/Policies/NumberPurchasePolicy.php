<?php

namespace App\Policies;

use App\Models\NumberPurchase;
use App\Models\User;

class NumberPurchasePolicy
{
    public function view(User $auth, NumberPurchase $purchase): bool
    {
        return $purchase->user_id === $auth->id || $auth->isAdmin();
    }

    public function viewInbox(User $auth, NumberPurchase $purchase): bool
    {
        return $purchase->user_id === $auth->id;
    }

    public function manage(User $auth, NumberPurchase $purchase): bool
    {
        return $purchase->user_id === $auth->id;
    }
}
