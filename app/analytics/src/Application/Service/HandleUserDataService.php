<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\User;
use App\Repository\UserRepository;

final class HandleUserDataService implements HandleUserDataUseCase
{
    private UserRepository $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function execute(HandleUserData $command): User
    {
        $existingUser = $this->users->findOneBy(['publicId' => $command->user()->getPublicId()]);

        if (isset($existingUser)) {
            return $existingUser;
        }

        $newUser = $command->user();
        return $this->users->add($newUser);
    }
}
