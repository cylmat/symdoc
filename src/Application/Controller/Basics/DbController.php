<?php

namespace App\Application\Controller\Basics;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DbController extends AbstractController
{
    /**
     * @Route("/doctrine")
     */
    public function doctrine(): Response
    {
        return $this->render('db/doctrine.html.twig', [
            'controller_name' => 'DoctrineController',
        ]);
    }
}
