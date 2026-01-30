<?php

declare(strict_types=1);

namespace App\Product\App;

use App\Product\App\ProductData;
use App\Product\Domain\Models\Product;

interface ProductQueryServiceInterface
{
    /**
     * @return ProductData[]
     */
    public function getProductsList(): array;
    
    public function findProductById(int $id): ?ProductData;

    public function getProductImages(int $id): ?array;
}