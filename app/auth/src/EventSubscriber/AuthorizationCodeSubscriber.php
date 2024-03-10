<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use League\Bundle\OAuth2ServerBundle\Event\AuthorizationRequestResolveEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\FirewallMapInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AuthorizationCodeSubscriber implements EventSubscriberInterface
{
    use TargetPathTrait;

    private Security $security;
    private UrlGeneratorInterface $urlGenerator;
    private RequestStack $requestStack;
    private FirewallMapInterface $firewallMap;

    public function __construct(
        Security              $security,
        UrlGeneratorInterface $urlGenerator,
        RequestStack          $requestStack,
        FirewallMapInterface  $firewallMap
    )
    {
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
        $this->firewallMap = $firewallMap;
    }

    public function onAuthorizationRequestResolve(AuthorizationRequestResolveEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $user = $this->security->getUser();

        $firewallName = $this->firewallMap->getFirewallConfig(
            $this->requestStack->getCurrentRequest()
        )->getName();

        $this->saveTargetPath($request->getSession(), $firewallName, $request->getUri());

        $response = new RedirectResponse($this->urlGenerator->generate('app_login'), 307);

        if ($user instanceof UserInterface) {
            $event->resolveAuthorization(
                AuthorizationRequestResolveEvent::AUTHORIZATION_APPROVED
            );

            return;
        }

        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'league.oauth2_server.event.authorization_request_resolve' =>
                'onAuthorizationRequestResolve',
        ];
    }
}
