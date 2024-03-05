<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/api/user', name: 'app_api')]
    public function index(): Response
    {

        return $this->json([
            "test" => "test"
        ]);

//        /** @var User $user */
//        $user = $this->security->getUser();
//
//        return $this->json([
//            'email' => $user->getEmail(),
//            'uuid' => $user->getUserIdentifier(),
//        ]);
    }
}
