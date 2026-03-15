<?php

declare(strict_types=1);

namespace App\User\API;

use App\User\App\User;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Product\App\Model\Product;

interface UserAPIInterface
{
    public function createUser(User $user): void;

    public static function isUserCreatorOf(UserInterface $user, Product|array $product): bool;
    
}