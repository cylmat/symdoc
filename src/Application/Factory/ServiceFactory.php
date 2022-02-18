<?php

namespace App\Application\Factory;

use App\Application\Service\FromFactoryService;

final class ServiceFactory
{
    public function createFromFactoryService(string $id): object
    {
        return new FromFactoryService($id);
    }
}
