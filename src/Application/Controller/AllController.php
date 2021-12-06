<?php

namespace App\Application\Controller;

use App\Application\Service\TwitterClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllController extends AbstractController
{
    /**
     * @Route("/all")
     */
    public function index(TwitterClient $t): Response
    {
        return $this->render('all/index.html.twig', [
            'controller_name' => 'AllController',
        ]);
    }
}
