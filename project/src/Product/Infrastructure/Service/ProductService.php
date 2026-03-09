<?php

declare (strict_types = 1);

namespace App\Product\Infrastructure\Service;

use App\Product\App\Model\Product;
use App\Product\App\Model\SaveProductData;
use App\Product\App\Repository\ProductRepositoryInterface;
use App\Product\App\Service\ImageServiceInterface;
use App\Product\App\Service\ProductServiceInterface;
use App\Product\Infrastructure\Service\ValidationService;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $repository,
        private ImageServiceInterface $imageService
    )
    {}

    public function create(SaveProductData $product): void
    {
        ValidationService::validateProduct($product);
        $newFile = $this->imageService->saveImage($product->getImage());
        $newProduct = new SaveProductData(
            $product->getTitle(),
            $product->getPrice(),
            $product->getDescription(),
            $newFile
        );
        $this->repository->store($newProduct);
    }

    public function delete(int $id): void
    {
        $productData = $this->repository->findProduct($id);

        if ($productData === null)
        {
            return;
        }

        $this->imageService->deleteImage($productData['image']);
        $this->repository->delete($id);
    }

    public function update(Product $product): void
    {
        $currentProduct = $this->repository->findProduct($product->getId());

        if ($currentProduct === null)
        {
            return;
        }

        ValidationService::validateProduct($product);

        $previousFilename = $currentProduct['image'];
        $newFile = $product->getImage();
        if ($previousFilename !== $product->getImage()->getBasename())
        {
            //новый (загруженный) файл
            $newFile = $this->imageService->updateImage($previousFilename, $product->getImage());
        }

        $newProduct = new Product(
            $product->getId(),
            $product->getTitle(),
            $product->getPrice(),
            $product->getDescription(),
            $newFile
        );

        $this->repository->store($newProduct);
    }
}
