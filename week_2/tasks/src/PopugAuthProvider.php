<?php

namespace App;

use App\Entity\User;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

final class PopugAuthProvider extends AbstractProvider
{
    public function getBaseAuthorizationUrl()
    {
        return getenv('OAUTH2_AUTHORIZATION_URL');
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return getenv('OAUTH2_ACCESS_TOKEN_URL');
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return getenv('OAUTH2_USER_RESOURCES_URL');
    }

    protected function getDefaultScopes()
    {
        return ['email'];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new User();
    }
}
