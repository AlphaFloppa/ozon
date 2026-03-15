<?php

declare (strict_types = 1);

namespace App\User\Infrastructure;

use App\User\App\Query\UserQueryServiceInterface;
use App\User\App\User;
use PDO;

class UserQueryService implements UserQueryServiceInterface
{
    public function __construct
    (
        private PDO $pdo
    )
    {
        }

    public function findUserByEmail(string $email): ?User
    {
        $query = <<<SQL
            SELECT * FROM user
            WHERE email = :email
        SQL;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(
            [
                'email' => $email,
            ]
        );
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (! $data)
        {
            return null;
        }

        return new User(
            (string) $data['id'],
            $email,
            $data['password_hash'],
            (float) $data['balance'],
            strtoupper($data['role'])
        );
    }
}