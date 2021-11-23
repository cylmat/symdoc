<?php

namespace App\Application\Controller;

use App\Domain\Manager\RedisManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RedisController extends AbstractController
{
    /**
     * @Route("/redis")
     */
    public function index(RedisManager $redisManager): Response
    {
        $redisManager->call();

        return $this->render('redis/index.html.twig', [
            'controller_name' => 'RedisController',
        ]);
    }
}
