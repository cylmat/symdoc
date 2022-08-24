<?php

namespace App\Application\Controller;

use App\Application\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\InMemoryFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
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

    /**
     * @Route("/remote")
     * @IsGranted("ROLE_USER")
     */
    public function remote(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', 'subject', 'User tried to access a page without having ROLE_USER');

        if ($this->security->isGranted('ROLE_ADMIN')) {
            // ...
        }

        return new Response([
            'data' => [
                'user' => $this->getUser(), // caution: ROLES is empty!
                'security' => $this->security, // caution: ROLES is empty!
                'token' => $this->security->getToken(), // PreAuthenticatedToken
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
