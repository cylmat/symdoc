<?php

namespace App\Application\Controller\Basics;

use App\Application\Response;
use App\Domain\Manager\RedisManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

final class CacheController extends AbstractController
{
    /**
     * @Route("/cache")
     */
    public function redis(RedisManager $redisManager): Response
    {
        return new Response([
            'data' => $redisManager->call(),
        ]);
    }
}
