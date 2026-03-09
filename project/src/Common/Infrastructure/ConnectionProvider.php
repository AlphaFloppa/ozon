<?php

declare (strict_types = 1);

namespace App\Common\Infrastructure;

use PDO;

class ConnectionProvider
{
    /**
     * @param EnvProvider $provider
     */
    public function __construct(
        private readonly EnvProvider $provider
    )
    {}

    /**
     * @return PDO подключение к бд
     */
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
