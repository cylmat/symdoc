<?php

namespace App\Application\Controller\Basics;

use App\Domain\Manager\RedisManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CacheController extends AbstractController
{
    /**
     * @Route("/cache")
     */
    public function cache(RedisManager $redisManager): Response
    {
        return $this->render('db/redis.html.twig', [
            'controller_name' => 'RedisController',
            'data' => $redisManager->call(),
        ]);
    }
}
