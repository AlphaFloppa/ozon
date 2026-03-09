<?php

declare (strict_types = 1);

namespace App\Product\API;

use App\Product\App\Model\Product;
use App\Product\App\Model\SaveProductData;

interface ProductAPIInterface
{
    /**
     * @param int $id ID продукта
     */
    public function deleteProduct(int $id): void;

    /**
     * @param int $id ID продукта
     * @return array|null продукт в виде ассоциативного массива
     */
    public function findProduct(int $id): ?array;

    /**
     * @return array список продуктов
     */
    public function getProductList(): array;

    /**
     * @param SaveProductData сохраняемый продукт
     */
    public function saveProduct(SaveProductData $product): void;

    /**
     * @param Product обновляемый продукт
     */
    public function updateProduct(Product $product): void;
}
