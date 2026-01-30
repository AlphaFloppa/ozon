<?php

declare(strict_types=1);

namespace App\Common\Infrastructure;

use PDO;

class ConnectionProvider
{
    public function __construct(
        private readonly EnvProvider $provider
    )
    {}

    public function getConnectionToDb(): PDO
    {
        $params = $this->provider->getDBParams();
        return new PDO(
            $params["dsn"],
            $params["login"],
            $params["password"]
        );
    }
}