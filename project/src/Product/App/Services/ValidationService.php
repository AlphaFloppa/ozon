<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Services;

use App\Product\App\Models\Product;
use App\Product\App\Exceptions\PriceException;

class ValidationService
{
    public static function validateProduct(Product $product): void {
        if ($product->getPrice() < 0) {
            throw new PriceException($product->getPrice());
        }
    }
}