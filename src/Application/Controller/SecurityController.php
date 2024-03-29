<?php

namespace App\Application\Controller;

use App\Application\Response;
use App\Application\Security\AppUser;
use App\Application\Security\CustomQueryAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\InMemoryFactory;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Firewall\RemoteUserAuthenticationListener;

/**
 * SECURITY
 *
 * GuardAuthentication: Control auth process
 * Token: Represents the user authentication data present in the request
 * UserProvider: Retrieve and provide a User class
 * User: Type of user having username, password and others
 *
 *
 *
 * IS_AUTHENTICATED_ANONYMOUSLY: All users (even anonymous ones) have this.
 * IS_AUTHENTICATED_FULLY: This is similar to IS_AUTHENTICATED_REMEMBERED, but stronger.
 * IS_AUTHENTICATED_REMEMBERED: All logged users have this,
 *      even if they are logged in because of a "remember me cookie".
 */

// composer require symfony/security-bundle
// php bin/console security:encode-password

/**
 * https://symfony.com/doc/5.0/security/custom_authentication_provider.html
 * + custom token
 * + custom listener
 * + custom provider
 * + factory  SecurityFactoryInterface
 *      # DependencyInjection\Security\Factory\AbstractFactory
 */
/**
 * @IsGranted("ROLE_USER")
 */
final class SecurityController extends AbstractController
{
    private $security;

    // security.helper
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    // for json
    /**
     * @Route("/login_from_json", name="login", methods={"POST"})
     */

    /**
     * @Route("/security", name="app_secure_route", schemes={"http"})
     * @IsGranted("ROLE_USER")
     * @IsGranted("EXTRA")
     */
    public function security(
        Request $request,
        GuardAuthenticatorHandler $guardHandler
    ): HttpFoundationResponse { //, InMemoryUserProvider $userProvider
        $this->denyAccessUnlessGranted('ROLE_USER', 'subject', 'User tried to access a page without having ROLE_USER');
        $this->denyAccessUnlessGranted('EXTRA', 'sub');

        /*$this->denyAccessUnlessGranted(new Expression(
            '"ROLE_ADMIN" in role_names or (not is_anonymous() and user.isSuperAdmin())'
        ));*/

        if ($this->security->isGranted('ROLE_ADMIN')) {
            // ...
        }

        // Manually auth
        //  authenticate the user and use onAuthenticationSuccess on the authenticator
        // $manually = $guardHandler->authenticateUserAndHandleSuccess(
        //     (new AppUser())->setUsername('man'),
        //     $request,
        //     $customQueryAuth,
        //     'remote',
        // );

        return new Response([
            'data' => [
                'user' => $this->getUser(), // caution: ROLES is empty!
                'security' => $this->security, // caution: ROLES is empty!
                'token' => $this->security->getToken(), // PreAuthenticatedToken,
                'query_auth' => $request->getSession()->get('QUERY_AUTH'),
                'session' => $request->getSession(),
                //'manually' => $manually,
                'cookies' => $request->cookies,
            ],
        ]);
    }

    /**
     * # Route("/basic")
     */
    public function basic(Request $request): Response
    {
        return new Response([
            'data' => [],
        ]);
    }

    /**
     * # Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
        throw new \Exception('Never executed');
    }
}
