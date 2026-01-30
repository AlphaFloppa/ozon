<?php

declare(strict_types=1);

namespace App\Product\API;

use App\Product\App\ProductData;
use App\Product\Domain\Models\Product;

interface ProductAPIInterface
{
    /**
     * @return ProductData[]
     */
    public function getPublicProductImagesDirectory(): string;

    public function getProductsList(): array;

    public function findProduct(int $id): ?ProductData;

    public function createProduct(Product $product): void;

    public function deleteProduct(int $id): void;
}