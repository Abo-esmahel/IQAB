<?php

namespace App\Exceptions;

use Exception;

class ProviderTimeoutException extends Exception
{
    public static function connection(string $message): static
    {
        return new static($message);
    }
}
