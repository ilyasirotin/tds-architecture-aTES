<?php

namespace App\Message;

use Symfony\Component\Security\Core\User\UserInterface;

final class UserCreatedMessage
{
    private UserInterface $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
}
