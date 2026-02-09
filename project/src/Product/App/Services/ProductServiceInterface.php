<?php

declare(strict_types=1);

namespace App\Product\App\Services;

use App\Product\App\Models\Product;
interface ProductServiceInterface
{
    public function create(Product $product): void;

    public function update(Product $product);

    public function delete(int $id): void;
}