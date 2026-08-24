<?php

namespace App\Policies;

use App\Models\ServicePurchase;
use App\Models\User;

class ServicePurchasePolicy
{
    public function view(User $auth, ServicePurchase $purchase): bool
    {
        return $purchase->user_id === $auth->id || $auth->isAdmin();
    }
}
