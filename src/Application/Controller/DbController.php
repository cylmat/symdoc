<?php

namespace App\Application\Controller;

use App\Domain\Manager\RedisManager;
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
        return $this->render('doctrine/index.html.twig', [
            'controller_name' => 'DoctrineController',
        ]);
    }

    /**
     * @Route("/redis")
     */
    public function redis(RedisManager $redisManager): Response
    {
        return $this->render('redis/index.html.twig', [
            'controller_name' => 'RedisController',
            'data' => $redisManager->call(),
        ]);
    }
}
