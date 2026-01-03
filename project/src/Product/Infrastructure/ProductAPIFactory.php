<?php

declare(strict_types=1);

namespace App\Product\Infrastructure;

use App\Product\API\ProductAPIInterface;
use App\Product\API\ProductAPI;
use App\Product\App\ProductQueryServiceInterface;
use App\Product\App\ProductRepositoryInterface;

class ProductAPIFactory
{
    public static function create(
        ProductQueryServiceInterface $queryService,
        ProductRepositoryInterface $repository
    ): ProductAPIInterface
    {
        return new ProductAPI(
            $queryService,
            $repository
        );
    }
}