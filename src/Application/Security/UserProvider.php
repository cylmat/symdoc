<?php

namespace App\Application\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 *  Reloading the User data from the session, like remember me and impersonation...
 * Verify new user in Security\Core's AbstractToken
 */
class UserProvider implements
    UserProviderInterface,
    PasswordUpgraderInterface,
    EquatableInterface // compare user when refreshed
{
    public function isEqualTo(UserInterface $user)
    {
        if (
            $this->user->getPassword() === $user->getPassword()
            && $this->user->getSalt() !== $user->getSalt()
        ) {
            return true;
        }
    }

    /**
     * Symfony calls this method if you use features like switch_user or remember_me.
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username): UserInterface
    {
        dump('load user : ' . $username);
        if ('hulluby' === $username) {
            return (new AppUser())->setUsername($username . '@1')->setPassword('s@ecur');
        }

        if ('john' === $username) {
            return (new AppUser())->setUsername($username);
        }

        if ('alphonse' === $username) {
            return (new AppUser())->setUsername($username);
        }

        // Load a User object from your data source or throw UsernameNotFoundException.
        // The $username argument may not actually be a username:
        // it is whatever value is being returned by the getUsername()
        // method in your User class.

        throw new \Exception('TODO: fill in loadUserByUsername() inside ' . __FILE__);
    }

    /**
     * Refreshes the user after being reloaded from the session.
     *
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User data.
     *
     * If your firewall is "stateless: true" (for a pure API), this
     * method is not called.
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof AppUser) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        dump('refreshing user');
        return $user->setPassword('s@ecur');


        // Return a User object after making sure its data is "fresh".
        // Or throw a UsernameNotFoundException if the user no longer exists.
        throw new \Exception('TODO: fill in refreshUser() inside ' . __FILE__);
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass($class): bool
    {
        return AppUser::class === $class || is_subclass_of($class, AppUser::class);
    }

    /**
     * Upgrades the hashed password of a user, typically for using a better hash algorithm.
     */
    public function upgradePassword(UserInterface $user, string $newHashedPassword): void
    {
        dump('new hash', $newHashedPassword);
        // TODO: when hashed passwords are in use, this method should:
        // 1. persist the new password in the user storage
        // 2. update the $user object with $user->setPassword($newHashedPassword);
    }
}