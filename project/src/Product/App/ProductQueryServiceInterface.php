<?php

declare(strict_types=1);

namespace App\Product\App;

use App\Product\App\ProductData;

interface ProductQueryServiceInterface
{
    /**
     * @return ProductData[]
     */
    public function getProductsList(): array;

    public function findProduct(int $id): ?ProductData;
}