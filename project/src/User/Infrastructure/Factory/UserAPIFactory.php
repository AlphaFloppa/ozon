<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Factory;

use App\User\API\UserAPI;
use App\User\API\UserAPIInterface;
use App\User\Infrastructure\Repository\UserRepository;
use App\User\Infrastructure\UserQueryService;
use PDO;

class UserAPIFactory
{
    public static function create(PDO $connection): UserAPIInterface
    {
        return new UserAPI(
            new UserQueryService($connection),
            new UserRepository($connection)
        );
    }
}

