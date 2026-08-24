<?php

namespace App\Exceptions;

use Exception;

class ProviderException extends Exception
{
    public static function failed(string $message): static
    {
        return new static($message);
    }
}
