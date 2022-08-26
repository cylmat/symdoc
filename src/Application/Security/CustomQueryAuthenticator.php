<?php

namespace App\Application\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Guard\AuthenticatorInterface;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Guard\Token\GuardTokenInterface;

# made with bin/console make:auth
/**
 * Custom for SSO for exemple, or WS-Security (WSSE)
 * https://symfony.com/doc/5.0/security/custom_authentication_provider.html
 */
class CustomQueryAuthenticator extends AbstractGuardAuthenticator implements
    AuthenticatorInterface,
    # /srv/sym/doc/vendor/symfony/security-guard/Provider/GuardAuthenticationProvider.php
    PasswordAuthenticatedInterface # to use Encoder functions
{
    private array $serverPass;

    public function __construct(RequestStack $request)
    {
        $server = $request->getMasterRequest()->server->get('query_user');
        $server = \explode(',', $server);

        foreach ($server as $userpass) {
            $up = \explode(':', $userpass);
            $this->serverPass[$up[0]] = $up[1];
        }
    }

    public function supports(Request $request)
    {
        //check route
        if ('app_secure_route' === $request->attributes->get('_route') && $request->isMethod('GET')) {
            # ex: return $request->headers->has('X-AUTH-TOKEN');
            return (null !== $request->query->get('user') && null !== $request->query->get('pass'));
        }
        return false;
    }

    # current user login credentials from request
    public function getCredentials(Request $request)
    {
        # ex: return $request->headers->get('X-AUTH-TOKEN');
        return [
            'user' => $request->query->get('user'),
            'pass' => $request->query->get('pass'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        $existingPass = $this->serverPass[$credentials['user']] ?? null;
        return $userProvider->loadUserByUsername($credentials['user'])->setPassword($existingPass);
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $credentials['pass'] === $user->getPassword();
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->remove('QUERY_AUTH');
        $request->getSession()->set('QUERY_AUTH', 'no');

        if (false === 'ILuvAPIs') {
            throw new CustomUserMessageAuthenticationException();
        }

        return new JsonResponse('fail', Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        $request->getSession()->set('QUERY_AUTH', 'ok');

        // USE WITH REMEMBER ME
        //return new RedirectResponse('/security');
    }

    /**
     * "entry point"
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        d('start');
    }

    public function supportsRememberMe()
    {
        return false; //return true;
    }

    // used for migrate hash
    public function getPassword($credentials): ?string
    {
        return $credentials['pass'];
    }
}
