<?php

declare(strict_types=1);

namespace App\Product\App\Repository;

use App\Product\App\Models\Product;

interface ProductRepositoryInterface
{
    public function store(Product $product): void;

    public function delete(int $id): void;
}