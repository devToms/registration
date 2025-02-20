<?php

namespace App\Service;

use App\Entity\User;

interface RegistrationServiceInterface
{
    public function registerUser(User $user): bool;
}
