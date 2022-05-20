<?php

namespace App\Application\Form\Object;

class IntObject
{
    private $int;

    public function __construct(int $int)
    {
        $this->int = $int;
    }

    public function getInt(): int
    {
        return $this->int;
    }
}
