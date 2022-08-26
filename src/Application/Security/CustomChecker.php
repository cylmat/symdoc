<?php

namespace App\Application\Security;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

// run in GuardAuthenticationProvider
class CustomChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        dump('checkpre');
        if (!$user instanceof AppUser) {
            return;
        }

        if ($user->sampleCheck()) {
            throw new CustomUserMessageAuthenticationException('...');
        }
    }

    # PreAuthenticatedAuthenticationProvider
    public function checkPostAuth(UserInterface $user)
    {
        dump('checkpost');
        if (!$user instanceof AppUser) {
            return;
        }

        if ($user->sampleCheck()) {
            throw new CustomUserMessageAuthenticationException('...');
        }
    }
}
