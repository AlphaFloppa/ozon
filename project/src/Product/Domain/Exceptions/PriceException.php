<?php

declare(strict_types = 1);

namespace App\Product\Domain\Exceptions;

use Exception;
class PriceException extends Exception
{
    public function __construct(float $price)
    {
        parent::__construct("Price cant be negative, got $price");
    }
}