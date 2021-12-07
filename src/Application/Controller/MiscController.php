<?php

namespace App\Application\Controller;

use App\Domain\Manager\MiscManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MiscController extends AbstractController
{
    /**
     * @Route("/misc")
     */
    public function index(MiscManager $miscManager): Response
    {
        $miscManager->call();

        return $this->render('misc/index.html.twig', [
            'controller_name' => 'MiscController',
        ]);
    }
}
