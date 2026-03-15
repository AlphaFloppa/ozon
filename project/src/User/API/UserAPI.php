<?php

declare(strict_types=1);

namespace App\User\API;

use App\User\App\Query\UserQueryServiceInterface;
use App\User\App\User;
use App\User\Query\UserRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Product\App\Model\Product;

class UserAPI implements UserAPIInterface
{
    public function __construct(
        private UserQueryServiceInterface $queryService,
        private UserRepositoryInterface $repository,
    )
    {}

    public function createUser(User $user): void
    {
        $this->repository->createUser($user);
    }

    public static function isUserCreatorOf(UserInterface $user, Product|array $product): bool
    {
        if ($product instanceof Product) {
            return $user->getUserIdentifier() === $product->getSellerId();
        }

        return $user->getUserIdentifier() === $product['seller_id'];
    }
}