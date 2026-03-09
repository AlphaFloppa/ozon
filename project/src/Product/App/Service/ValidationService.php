<?php

declare (strict_types = 1);

namespace App\Product\Infrastructure\Service;

use App\Product\App\Exception\PriceException;
use App\Product\App\Model\Product;
use App\Product\App\Model\SaveProductData;

class ValidationService
{
    /**
     * @param Product|SaveProductData $product валидируемый продукт
     */
    public static function validateProduct(Product | SaveProductData $product): void
    {
        if ($product->getPrice() < 0) {
            throw new PriceException($product->getPrice());
        }
    }
}
