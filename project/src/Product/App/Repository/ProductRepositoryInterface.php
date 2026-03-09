<?php

declare (strict_types = 1);

namespace App\Product\App\Repository;

use App\Product\App\Model\Product;
use App\Product\App\Model\SaveProductData;

interface ProductRepositoryInterface
{
    /**
     * @param Product|SaveProductData продукт
     */
    public function store(Product | SaveProductData $product): void;

    /**
     * @return array|null продукт или отсутствие
     * @param int ID_продукта
     */
    public function findProduct(int $id): array | null;

    /**
     * @param int ID_продукта
     */
    public function delete(int $id): void;
}
