<?php

namespace App\Application\Service;

class Rot13Transformer
{
    private $transform;

    public function __construct(bool $transform)
    {
        $this->transform = $transform;
    }

    public function transform(string $value): string
    {
        return $this->transform ? str_rot13($value) : $value;
    }
}
