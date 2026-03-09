<?php

declare(strict_types=1);

namespace App\Product\App\Query;

interface ProductQueryServiceInterface
{
    /**
     * @return array массив продуктов
     */
    public function getProductsList(): array;
    
    /**
     * @param int ID_продукта
     * @return array ассоциативный массив ProductData
     */
    public function findProductById(int $id): ?array;
}