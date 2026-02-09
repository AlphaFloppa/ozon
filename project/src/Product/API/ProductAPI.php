<?php

declare(strict_types=1);

namespace App\Product\API;

use App\Product\API\ProductAPIInterface;
use App\Product\App\Query\ProductQueryServiceInterface;
use App\Product\App\Models\Product;
use App\Product\App\Services\ImageServiceInterface;
use App\Product\App\Services\ProductServiceInterface;
use App\Product\Infrastructure\Services\ValidationService;

class ProductAPI implements ProductAPIInterface
{
    public function __construct(
        private readonly ProductQueryServiceInterface $queryService,
        private readonly ProductServiceInterface $productService,
        private readonly ImageServiceInterface $imageService
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

    public function saveProduct(Product $product): void
    {
        ValidationService::validateProduct($product);
        $newFile = $this->imageService->saveImage($product->getImage());
        $newProduct = new Product(
            null,
            $product->getTitle(),
            $product->getPrice(),
            $product->getDescription(),
            $newFile
        );
        $this->productService->create($newProduct);
    }

    public function updateProduct(Product $product): void
    {
        ValidationService::validateProduct($product);
        $previousFilename = $this->queryService
            ->findProductById($product->getId())['image'];
        $newFile = $product->getImage();
        if ($previousFilename !== $product->getImage()->getBasename()) {            //новый (загруженный) файл
            $newFile = $this->imageService->updateImage($previousFilename, $product->getImage());
        }
        $newProduct = new Product(
            $product->getId(),
            $product->getTitle(),
            $product->getPrice(),
            $product->getDescription(),
            $newFile
        );
        $this->productService->update($newProduct);
    }

    public function deleteProduct(int $id): void
    {
        $productData = $this->queryService->findProductById($id);
        if (!$productData) {
            return;
        }
        $this->imageService->deleteImage($productData['image']);
        $this->productService->delete($id);
    }
}