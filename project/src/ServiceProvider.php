<?php

declare(strict_types=1);

namespace App;

use App\Common\Infrastructure\ConnectionProvider;
use App\Product\API\ProductAPIInterface;
use App\Product\Infrastructure\Factory\ProductAPIFactory;
use App\User\API\UserAPIInterface;
use App\User\Infrastructure\Factory\UserAPIFactory;
use PDO;

class ServiceProvider
{
    private PDO $pdo;
    public function __construct(
        private readonly ConnectionProvider $provider
    )
    {
        $this->pdo = $this->provider->getConnectionToDb();
    }

    public function getProductAPI(): ProductAPIInterface
    {
        return ProductAPIFactory::create($this->pdo);
    }

    public function getUserAPI(): UserAPIInterface
    {
        return UserAPIFactory::create($this->pdo);
    }
}