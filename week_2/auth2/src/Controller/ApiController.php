<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/api/user', name: 'app_api')]
    public function index(): Response
    {
        return $this->json([
            'uuid' => $this->getUser()->getUserIdentifier(),
            'email' => $this->getUser()->getEmail(),
        ]);
    }
}
