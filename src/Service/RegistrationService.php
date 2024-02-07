<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function registerUser(User $user): bool
    {
        $existingUser = $this->userRepository->findByUserEmail($user->getEmail());
        if ($existingUser) {
            return false;
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());

        $user->setPassword($hashedPassword)->setRoles($user->getRoles());

        $this->userRepository->save($user);

        return true;
    }
}
