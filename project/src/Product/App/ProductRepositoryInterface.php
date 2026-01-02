<?php

declare(strict_types=1);

namespace App\Product\App;

use App\Product\App\ProductData;
use App\Product\Domain\Product;

interface ProductRepositoryInterface
{
    public function findProduct(int $id): ProductData;

    public function store(ProductData $data): void;

    public function delete(int $id): void;
}