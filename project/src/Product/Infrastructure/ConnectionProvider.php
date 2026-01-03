<?php

declare(strict_types=1);

namespace App\Product\Infrastructure;

use PDO;

class ConnectionProvider
{
    private static string $configPath = __DIR__ . "/../../../config/db_params.json";
    //TODO: заменить на env

    public static function getConnectionParams(): array
    {
        return json_decode(
            file_get_contents(self::$configPath), 
            true
        );
    }

    public static function getConnectToDb(): PDO
    {
        $data = self::getConnectionParams();
        return new PDO(
            $data["dsn"],                           
            $data["userLogin"],
            $data["userPassword"]
        );
    }
}