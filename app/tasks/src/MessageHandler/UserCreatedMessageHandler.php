<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\UserCreatedMessage;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class UserCreatedMessageHandler
{
    private UserRepository $users;
    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $users, EntityManagerInterface $entityManager)
    {
        $this->users = $users;
        $this->entityManager = $entityManager;
    }

    public function __invoke(UserCreatedMessage $message)
    {
        $receivedUser = $message->getUser();

        $user = new User();

        $user->setUuid($receivedUser->getUuid());
        $user->setEmail($receivedUser->getEmail());
        $user->setUsername($receivedUser->getUsername());
        $user->setRoles($receivedUser->getRoles());
        $user->setActive($receivedUser->isActive());
        $user->setCreatedAt($receivedUser->getCreatedAt());
        $user->setUpdatedAt($receivedUser->getUpdatedAt());

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
