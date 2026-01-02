<?php

declare(strict_types=1);

namespace App\Product;

use App\Product\API\ProductAPIInterface;
use App\Product\Infrastructure\ProductAPIFactory;
use App\Product\Infrastructure\ProductQueryService;
use App\Product\Infrastructure\ProductRepository;

class ServiceProvider
{
    public static function getAPI(): ProductAPIInterface
    {
        return ProductAPIFactory::create(
            new ProductQueryService(),
            new ProductRepository()
        );
    }
}