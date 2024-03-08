<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Repository\UserRepository;

final class RegisterUserService implements RegisterUserUseCase
{
    private UserRepository $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function execute(RegisterUserCommand $command): void
    {
        $this->users->add($command->user());
    }
}
