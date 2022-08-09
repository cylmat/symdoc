<?php

namespace App\Domain\Manager\Advanced;

use App\Domain\Core\Interfaces\ManagerInterface;

final class ValidationManager implements ManagerInterface
{
    public function __construct()
    {
    }

    public function call(array $context = []): array
    {
        return [];
    }
}
