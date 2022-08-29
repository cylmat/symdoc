<?php

namespace App\Application\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Security;

/**
 * All voters are called with isGranted(), denyAccessUnlessGranted() (authorization checker), or by access controls.
 * tag: security.voter
 */
class CustomVoter extends Voter implements VoterInterface
{
    public function __construct(Security $security) // security.helper
    {
    }

    // isGranted() (or denyAccessUnlessGranted()) is called
    protected function supports(string $attribute, $subject) # bool
    {
        return 'EXTRA' === $attribute && 'sub' === $subject;
    }

    // called if supported!
    // return true to allow or false otherwise
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token) # bool
    {
        if ('EXTRA' === $attribute) {
            return true;
        }
        return false;
    }

    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        return parent::vote($token, $subject, $attributes);
    }
}
