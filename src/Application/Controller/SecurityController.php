<?php

namespace App\Application\Controller;

use App\Application\Response;
use App\Application\Security\AppUser;
use App\Application\Security\CustomQueryAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\InMemoryFactory;
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
 * Token: Contain informations about user for authentication manager
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
     * @Route("/remote", name="app_my_route")
     * @IsGranted("ROLE_USER")
     */
    public function remote(
        Request $request,
        GuardAuthenticatorHandler $guardHandler,
        CustomQueryAuthenticator $customQueryAuth
    ): HttpFoundationResponse { //, InMemoryUserProvider $userProvider
        $this->denyAccessUnlessGranted('ROLE_USER', 'subject', 'User tried to access a page without having ROLE_USER');

        if ($this->security->isGranted('ROLE_ADMIN')) {
            // ...
        }

        // redirect to user&pass to use CustomQueryAuth
        if (null === $request->query->get('user')) {
            return $this->redirectToRoute('app_my_route', ['user' => '', 'pass' => '']);
        }

        // Manually auth
        //  authenticate the user and use onAuthenticationSuccess on the authenticator
        $manually = $guardHandler->authenticateUserAndHandleSuccess(
            (new AppUser())->setUsername('man'),
            $request,
            $customQueryAuth,
            'remote',
        );

        return new Response([
            'data' => [
                'user' => $this->getUser(), // caution: ROLES is empty!
                'security' => $this->security, // caution: ROLES is empty!
                'token' => $this->security->getToken(), // PreAuthenticatedToken,
                'session' => $request->getSession()->get('QUERY_AUTH'),
                'manually' => $manually,
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
