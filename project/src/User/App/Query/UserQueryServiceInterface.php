<?php

declare(strict_types=1);

namespace App\User\App\Query;

use App\User\App\User;

interface UserQueryServiceInterface
{
    public function findUserByEmail(string $email): ?User;
}