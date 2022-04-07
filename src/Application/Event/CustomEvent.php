<?php

namespace App\Application\Event;

class CustomEvent
{
    private $type;
    private $value;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function running(): string
    {
        return $this->type . ' is running with - ' . $this->value;
    }
}
