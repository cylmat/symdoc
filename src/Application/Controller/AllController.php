<?php

namespace App\Application\Controller;

use App\Application\Response;
use App\Domain\Manager\AllManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

final class AllController extends AbstractController
{
    /**
     * @Route("/all")
     */
    public function index(AllManager $allManager): Response
    {
        return new Response([
            'data' => $allManager->call(),
        ]);
    }
}
