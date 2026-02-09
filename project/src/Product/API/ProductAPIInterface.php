<?php

declare(strict_types=1);

namespace App\Product\API;

use App\Product\App\Models\Product;

interface ProductAPIInterface
{
    public function getProductList(): array;

    public function findProduct(int $id): ?array;

    public function saveProduct(Product $product): void;

    public function updateProduct(Product $product): void;

    public function deleteProduct(int $id): void;
}