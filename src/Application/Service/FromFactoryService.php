<?php

namespace App\Application\Service;

class FromFactoryService
{
    public $mynameis;

    public function __construct(string $id)
    {
        $this->mynameis = "anonymous_{$id}_from_factory";
    }
}
