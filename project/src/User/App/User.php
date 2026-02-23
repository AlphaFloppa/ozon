<?php

declare(strict_types=1);

namespace App\User\App;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    private array $roles;

    public function __construct(
        private ?string $id,
        private string $email,
        private string $password,
        private float $balance,
        string $role
    )
    {
        $this->roles = ['ROLE_' . $role];
        $this->roles[] = 'ROLE_CUSTOMER';
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password_hash): void
    {
        $this->password = $password_hash;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function eraseCredentials(): void
    {
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUserIdentifier(): string
    {
        return $this->id;
    }
}