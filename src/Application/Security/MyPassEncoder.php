<?php

namespace App\Application\Security;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class MyPassEncoder implements PasswordEncoderInterface
{
    public static function isSupported(): bool
    {
        return true;
    }

    public function encodePassword(string $raw, ?string $salt)
    {
        return $raw;
    }

    public function isPasswordValid(string $encoded, string $raw, ?string $salt)
    {
        return true;
    }

    public function needsRehash(string $encoded): bool
    {
        return true;
    }
}
