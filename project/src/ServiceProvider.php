<?php

declare(strict_types=1);

namespace App;

use App\Common\Infrastructure\ConnectionProvider;
use App\Product\API\ProductAPIInterface;
use App\Product\Infrastructure\ProductAPIFactory;

class ServiceProvider
{
    public function __construct(
        private readonly ConnectionProvider $provider
    )
    {}

    public function getProductAPI(): ProductAPIInterface
    {
        return ProductAPIFactory::create(
            $this->provider->getConnectionToDb()
        );
    }
}