<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    #[Route('/login', name: 'app_auth')]
    public function auth(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry
            ->getClient('popug_oauth')
            ->redirect(['email']);
    }

    #[Route('/auth/callback', name: 'app_auth_callback')]
    public function callback(Request $request): Response
    {
        return $this->json($request);
    }
}
