<?php

namespace App\Product\Infrastructure\Exceptions;

use Exception;

class DatabaseException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}