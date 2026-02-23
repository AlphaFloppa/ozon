<?php

declare(strict_types=1);

namespace App\User;

use App\Common\Infrastructure\ConnectionProvider;
use App\User\Infrastructure\UserQueryService;
use App\User\App\User;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{

    private UserQueryService $queryService;

    public function __construct
    (
        ConnectionProvider $provider
    ) {
        $this->queryService = new UserQueryService($provider->getConnectionToDb());
    }

    public function loadUserByIdentifier(string $email): User
    {
        $user = $this->queryService->findUserByEmail($email);
        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }
}