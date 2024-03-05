<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    #[Route('/auth', name: 'app_auth')]
    public function auth(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('popug_oauth')
            ->redirect(['email']);
    }

    #[Route('/auth/callback', name: 'app_auth_callback')]
    public function callback()
    {
        /**
         * All logic implemented in symfony authenticator
         */
    }
}
