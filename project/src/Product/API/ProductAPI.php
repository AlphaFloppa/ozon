<?php

declare(strict_types=1);

namespace App\Product\API;

use App\Product\API\ProductAPIInterface;
use App\Product\App\Query\ProductQueryServiceInterface;
use App\Product\App\Model\Product;
use App\Product\App\Model\SaveProductData;
use App\Product\App\Service\ProductServiceInterface;

class ProductAPI implements ProductAPIInterface
{
    public function __construct(
        private readonly ProductQueryServiceInterface $queryService,
        private readonly ProductServiceInterface $productService
    ) {
    }

    public function getProductList(): array
    {
        return $this->queryService->getProductsList();
    }

    public function findProduct(int $id): ?array
    {
        return $this->queryService->findProductById($id);
    }

    public function saveProduct(SaveProductData $product): void
    {
        $this->productService->create($product);
    }

    public function updateProduct(Product $product): void
    {
        $this->productService->update($product);
    }

    public function deleteProduct(int $id): void
    {
        $this->productService->delete($id);
    }
}