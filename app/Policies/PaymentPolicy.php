<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function view(User $auth, Payment $payment): bool
    {
        return $payment->user_id === $auth->id || $auth->isAdmin();
    }
}
