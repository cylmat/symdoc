<?php

namespace App\Application\Controller;

use App\Domain\Manager\AllManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AllController extends AbstractController
{
    /**
     * @Route("/all")
     */
    public function index(AllManager $allManager): Response
    {
        $data = $allManager->call();

        return $this->render('all/index.html.twig', [
            'controller_name' => 'AllController',
            'data' => $data,
        ]);
    }
}
