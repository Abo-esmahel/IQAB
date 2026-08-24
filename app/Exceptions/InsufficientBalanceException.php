<?php

namespace App\Exceptions;

use Exception;

class InsufficientBalanceException extends Exception
{
    public static function make(float $required, float $available): static
    {
        return new static("Insufficient balance. Required: {$required}, Available: {$available}");
    }
}
