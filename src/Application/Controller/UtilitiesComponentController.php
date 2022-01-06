<?php

namespace App\Application\Controller;

use App\Application\Response;
use App\Domain\Manager\ComponentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UtilitiesComponentController extends AbstractController
{
    public function index(ComponentManager $compManager, array $context = []): Response
    {
        return new Response([
            'data' => $compManager->call($context),
        ]);
    }
}
