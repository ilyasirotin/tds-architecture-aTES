<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/api/user', name: 'app_api')]
    public function index(): Response
    {
        $user = $this->getUser();
        $user->eraseCredentials();

        return $this->json($user);
    }
}
