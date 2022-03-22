<?php

namespace App\Application\Service;

class Rot13Transformer
{
    private $transform;
    private $other;

    public function __construct(bool $transform, string $other)
    {
        $this->transform = $transform;
        $this->other = $other;
    }

    public function transform(string $value): string
    {
        return $this->transform ? str_rot13($value) : $value;
    }
}
