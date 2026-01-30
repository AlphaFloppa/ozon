<?php

declare(strict_types=1);

namespace App\Product\App;

use App\Product\Domain\Models\Product;

interface ProductRepositoryInterface
{
    public function store(Product $product): void;

    public function delete(int $id): void;
}