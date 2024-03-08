<?php

namespace App\Auth;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

final class AuthProvider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public function getBaseAuthorizationUrl(): string
    {
        return 'http://localhost:8080/authorize';
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

    protected function createResourceOwner(array $response, AccessToken $token): array
    {
        return $response;
    }
}
