<?php

namespace App\Users\Domain\Repository;

use App\Users\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function findByUlid(string $ulid): ?User;

    public function findByEmail(string $email): ?User;

    public function add(User $user, bool $doFlush = true): void;
}