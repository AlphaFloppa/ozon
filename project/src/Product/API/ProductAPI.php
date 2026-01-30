<?php

declare(strict_types=1);

namespace App\Product\API;

use App\Product\API\ProductAPIInterface;
use App\Product\App\ProductData;
use App\Product\App\ProductQueryServiceInterface;
use App\Product\App\ProductRepositoryInterface;
use App\Product\Domain\Models\Product;

class ProductAPI implements ProductAPIInterface
{
    public function __construct(
        private readonly ProductQueryServiceInterface $queryService,
        private readonly ProductRepositoryInterface $repository,
    )
    {}

    public function getPublicProductImagesDirectory(): string
    {
        return $this->repository->getPublicImagesStorageDirectory();
    }

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

    public function createProduct(Product $product): void
    {
        $movedFile = $this->repository->store($product->getImage());
        $product->setImage($movedFile);
        $this->queryService->createProduct(
            $product
        );
    }

    public function deleteProduct(int $id): void
    {
        $filename = $this->queryService->getProductImages($id)[0];
        $this->repository->delete($filename);
        $this->queryService->deleteProduct($id);
    }
}