<?php

namespace App\Auth;

use App\Entity\User;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

final class AuthProvider extends AbstractProvider
{
    public function getBaseAuthorizationUrl(): string
    {
        return 'http://auth.localhost/authorize';
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return 'http://auth/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return 'http://auth/api/user';
    }

    protected function getDefaultScopes(): array
    {
        return ['profile'];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        dump($response);
        exit;

//        return new User();
    }
}
