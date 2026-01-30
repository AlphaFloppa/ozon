<?php

declare(strict_types=1);

namespace App\Product\Infrastructure;

use App\Product\API\ProductAPIInterface;
use App\Product\API\ProductAPI;
use PDO;

class ProductAPIFactory
{
    public static function create(
        PDO $connection
    ): ProductAPIInterface
    {
        return new ProductAPI(
            new ProductQueryService($connection),
            new ProductRepository($connection)
        );
    }
}