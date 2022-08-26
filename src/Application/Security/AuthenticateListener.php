<?php

namespace App\Application\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Security\Http\Firewall\AbstractListener;

# can extends AbstractAuthenticationListener!
class AuthenticateListener extends AbstractListener implements EventSubscriberInterface
{
    #public function __invoke(RequestEvent $event) //in AbstractListener
    #{
    #    if (false !== $this->supports($event->getRequest())) {
    #    $this->authenticate($event);
    #}

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => 'authenticate',
        ];
    }

    public function supports(Request $request): ?bool
    {
        return true;
    }

    public function authenticate(RequestEvent $event)
    {
        dump('custom auth listener');
        if (false === $this->supports($event->getRequest())) {
            return;
        }

        $request = $event->getRequest();
        if (!$request->headers->has('x-wsse')) {
            //return;
        }

        $authToken = new CustomToken();
        // AuthenticationManagerInterface...

        try {
            //$authToken = $this->authenticationManager->authenticate($token);
            //$this->tokenStorage->setToken($authToken);

            return;
        } catch (AuthenticationException $failed) {
            // ... you might log something here

            // To deny the authentication clear the token. This will redirect to the login page.
            // Make sure to only clear your token, not those of other authentication listeners.
            // $token = $this->tokenStorage->getToken();
            // if ($token instanceof WsseUserToken && $this->providerKey === $token->getProviderKey()) {
            //     $this->tokenStorage->setToken(null);
            // }
            // return;
        }

        // By default deny authorization
        $response = new Response();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $event->setResponse($response);
    }
}
