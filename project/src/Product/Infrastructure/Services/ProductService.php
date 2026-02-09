<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Services;

use App\Product\App\Services\ProductServiceInterface;
use App\Product\Infrastructure\Repository\ProductRepository;
use PDO;
use App\Product\App\Models\Product;

class ProductService implements ProductServiceInterface
{
    private ProductRepository $repository;
    public function __construct(
        PDO $pdo
    ) {
        $this->repository = new ProductRepository($pdo);
    }

    public function create(Product $product): void
    {
        $this->repository->store($product);
    }

    public function update(Product $product): void        
    {
        $isAlreadyExist = $this->repository->isProductExist($product->getId());
        if (!$isAlreadyExist) {
            return;
        }
        $this->repository->store($product);
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }
}