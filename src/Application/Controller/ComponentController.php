<?php

namespace App\Application\Controller;

use App\Application\Response;
use App\Domain\Manager\ComponentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ComponentController extends AbstractController
{
    public function index(ComponentManager $miscManager): Response
    {
        return new Response([
            'data' => $miscManager->call(),
        ]);
    }
}
