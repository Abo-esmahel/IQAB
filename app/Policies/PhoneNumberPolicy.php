<?php

namespace App\Policies;

use App\Models\PhoneNumber;
use App\Models\User;

class PhoneNumberPolicy
{
    public function viewAny(User $auth): bool
    {
        return true;
    }

    public function view(User $auth, PhoneNumber $number): bool
    {
        return true;
    }

    public function purchase(User $auth, PhoneNumber $number): bool
    {
        return $number->status->value === 'available';
    }
}
