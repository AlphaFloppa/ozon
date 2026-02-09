<?php

declare(strict_types=1);

namespace App\Product\App\Query;

interface ProductQueryServiceInterface
{
    public function getProductsList(): array;
    
    public function findProductById(int $id): ?array;
}