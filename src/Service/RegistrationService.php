<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use App\Service\RegistrationServiceInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService implements RegistrationServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function registerUser(User $user): bool
    {
        $existingUser = $this->userRepository->findByUserEmail($user->getEmail());
        if ($existingUser) {
            return false;
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword)->setRoles($user->getRoles());
        $this->userRepository->save($user, true);

        return true;
    }
}
