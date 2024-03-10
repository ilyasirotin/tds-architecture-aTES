<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

final class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setEmail('popug-1@feather.com');
        $user1->setUsername('popug-1');
        $user1->setPublicId(Uuid::v7());
        $user1->setRoles([
            'ROLE_USER',
            'ROLE_ADMIN'
        ]);
        $user1->setPassword(
            $this->passwordHasher->hashPassword($user1, '1234')
        );

        $user2 = new User();
        $user2->setEmail('popug-2@feather.com');
        $user2->setUsername('popug-2');
        $user2->setPublicId(Uuid::v7());
        $user2->setRoles(['ROLE_USER']);
        $user2->setPassword(
            $this->passwordHasher->hashPassword($user1, '1234')
        );

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->flush();
    }
}
