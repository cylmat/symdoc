<?php

namespace App\Application\Controller;

use App\Application\Response;
use App\Domain\Manager\Utilities\ComponentManager;

final class UtilitiesComponentController
{
    public function outsidecalled(ComponentManager $compManager, array $context = []): Response
    {
        return new Response([
            'data' => $compManager->call($context),
        ]);
    }
}
