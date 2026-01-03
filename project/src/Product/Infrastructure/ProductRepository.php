<?php

declare(strict_types=1);

namespace App\Product\Infrastructure;

use App\Product\App\ProductRepositoryInterface;
use App\Product\App\ProductData;

class ProductRepository implements ProductRepositoryInterface
{
    public function findProduct(int $id): ProductData
    {

    }

    public function store(ProductData $data): void
    {
        
    }

    public function delete(int $id): void
    {
        
    }
}