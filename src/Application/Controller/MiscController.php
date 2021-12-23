<?php

namespace App\Application\Controller;

use App\Application\Response;
use App\Domain\Manager\MiscManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

final class MiscController extends AbstractController
{
    /**
     * @Route("/misc")
     */
    public function index(MiscManager $miscManager): Response
    {
        return new Response([
            'data' => $miscManager->call(),
        ]);
    }
}
