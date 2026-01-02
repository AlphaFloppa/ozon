<?php

declare(strict_types=1);

namespace App\Product\API;

use App\Product\App\ProductData;
use App\Product\App\ProductQueryServiceInterface;
use App\Product\App\ProductRepositoryInterface;

interface ProductAPIInterface
{
    /**
     * @return ProductData[]
     */
    public function getProductsList(): array;
    public function findProduct(int $id): ?ProductData;
}