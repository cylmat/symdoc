<?php

namespace App\Application\Controller;

use App\Application\Response;
use App\Domain\Manager\ComponentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

final class ComponentController extends AbstractController
{
    /**
     * @Route("/component")
     */
    public function index(ComponentManager $miscManager): Response
    {
        return new Response([
            'data' => $miscManager->call(),
        ]);
    }
}
