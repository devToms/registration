<?php

namespace App\Repository;

use App\Entity\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

interface UserRepositoryInterface
{
    public function save(User $entity, bool $flush = false): void;

    public function remove(User $entity, bool $flush = false): void;

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void;

    public function findByUserEmail($email): ?User;
}
