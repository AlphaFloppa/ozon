<?php

declare(strict_types=1);

namespace App\User\Query;

use App\User\App\User;

interface UserRepositoryInterface
{
    public function createUser(User $user): void;
}