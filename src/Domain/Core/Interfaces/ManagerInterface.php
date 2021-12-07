<?php

namespace App\Domain\Core\Interfaces;

interface ManagerInterface
{
    public function call(): array;
}