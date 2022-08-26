<?php

namespace App\Application\Security;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * A token represents the user authentication data present in the request.
 * Once a request is authenticated, the token retains the user's data,
 * and delivers this data across the security context.
 */

class CustomToken extends AbstractToken implements TokenInterface
{
    public function getRoleNames(): array
    {
        return $this->roleNames;
    }

    public function getUsername()
    {
        return parent::getUsername();
    }

    public function getUser()
    {
        return $this->user;
    }

    public function isAuthenticated()
    {
        return $this->authenticated;
    }

    public function getCredentials()
    {
    }
}
