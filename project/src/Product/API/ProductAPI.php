<?php

declare(strict_types=1);

namespace App\Product\API;

use App\Product\API\ProductAPIInterface;
use App\Product\App\ProductData;
use App\Product\App\ProductQueryServiceInterface;
use App\Product\App\ProductRepositoryInterface;

class ProductAPI implements ProductAPIInterface
{
    public function __construct(
        private readonly ProductQueryServiceInterface $queryService,
        private readonly ProductRepositoryInterface $repository,
    )
    {}

    /**
     * @return ProductData[]
     */
    public function getProductsList(): array
    {
        return $this->queryService->getProductsList();
    }

    public function findProduct(int $id): ?ProductData
    {
        return $this->queryService->findProduct($id);
    }
}