<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use PDO;
use App\User\App\User;
use App\User\Query\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private PDO $pdo
    ) 
    {}

    public function createUser(User $user): void
    {
        $upperRole = 'CUSTOMER';
        if (in_array('ROLE_SELLER', $user->getRoles())) {
            $upperRole = 'SELLER';
        }
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $upperRole = 'ADMIN';
        }
        $query = <<<SQL
            INSERT INTO user
            (email, password_hash, role, balance)
            VALUES 
            (:email, :password_hash, :role, 0)
        SQL;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(
            [
                'email' => $user->getEmail(),
                'password_hash' => $user->getPassword(),
                'role' => $upperRole,
            ]
        );
    }

}