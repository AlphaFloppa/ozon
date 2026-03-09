<?php

declare (strict_types = 1);

namespace App\Product\Infrastructure\Factory;

use App\Product\API\ProductAPI;
use App\Product\API\ProductAPIInterface;
use App\Product\Infrastructure\Query\ProductQueryService;
use App\Product\Infrastructure\Repository\ProductRepository;
use App\Product\Infrastructure\Service\ImageService;
use App\Product\Infrastructure\Service\ProductService;
use PDO;

class ProductAPIFactory
{
    public static function create(
        PDO $connection
    ): ProductAPIInterface
    {
        return new ProductAPI(
            new ProductQueryService($connection),
            new ProductService(
                new ProductRepository($connection),
                new ImageService()
            )
        );
    }
}
