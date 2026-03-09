<?php

declare(strict_types=1);

namespace App;

use App\Common\Infrastructure\ConnectionProvider;
use App\Product\API\ProductAPIInterface;
use App\Product\Infrastructure\Factory\ProductAPIFactory;

class ServiceProvider
{
    public function __construct(
        private readonly ConnectionProvider $provider
    )
    {}

    public function getProductAPI(): ProductAPIInterface
    {
        $pdo = $this->provider->getConnectionToDb();
        return ProductAPIFactory::create($pdo);
    }
}