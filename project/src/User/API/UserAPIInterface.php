<?php

declare(strict_types=1);

namespace App\User\API;

use App\User\App\User;

interface UserAPIInterface
{
    public function createUser(User $user): void;
}