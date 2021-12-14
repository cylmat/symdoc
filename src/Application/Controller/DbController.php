<?php

namespace App\Application\Controller;

use App\Domain\Manager\MessageManager;
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
        return $this->render('db/doctrine.html.twig', [
            'controller_name' => 'DoctrineController',
        ]);
    }

    /**
     * @Route("/redis")
     */
    public function redis(RedisManager $redisManager): Response
    {
        return $this->render('db/redis.html.twig', [
            'controller_name' => 'RedisController',
            'data' => $redisManager->call(),
        ]);
    }

    /**
     * @Route("/message")
     */
    public function message(MessageManager $messageManager): Response
    {
        return $this->render('db/message.html.twig', [
            'controller_name' => 'MessageController',
            'data' => $messageManager->call(),
        ]);
    }
}
