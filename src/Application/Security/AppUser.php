<?php

namespace App\Application\Security;

use Stringable;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AppUser implements UserInterface, Stringable, EncoderAwareInterface
{
    private string $username;

    private array $roles = [];

    /**
     * @var string The hashed password
     */
    private string $password;

    public function __toString(): string
    {
        return 'AppUser ' . $this->username;
    }

    // Used to select dynamically encoder
    public function getEncoderName()
    {
        if (1) {
            return 'myencoder';
        }

        return null; // use the default encoder
    }

    public function sampleCheck() // for user checker
    {
        return false;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        $roles[] = 'ROLE_CUSTOM';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return 9;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        //$this->plainPassword = null;
    }
}
