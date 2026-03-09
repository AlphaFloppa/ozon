<?php

declare(strict_types=1);

namespace App\Product\App\Service;

use App\Product\App\Model\Product;
use App\Product\App\Model\SaveProductData;

interface ProductServiceInterface
{
    /**
     * @param SaveProductData $product сохраняемый продукт
     */
    public function create(SaveProductData $product): void;

    /**
     * @param Product $product продукт
     */
    public function update(Product $product): void;

    /**
     * @param int $id ID продукта
     */
    public function delete(int $id): void;
}