<?php

namespace App\Domain\Model;

class ValueObject
{
    private $foo;

    public function __construct($bar)
    {
        $this->foo = $bar;
    }

    public function getFoo()
    {
        return $this->foo;
    }
}
