<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    #[Route('/login/', name: 'app_login')]
    public function auth(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry
            ->getClient('popug_oauth')
            ->redirect(['profile']);
    }

    #[Route('/login/callback', name: 'app_login_callback')]
    #[Route('/login/callback/', name: 'app_login_callback')]
    public function callback(Request $request): Response
    {
        return $this->json($request);
    }
}
