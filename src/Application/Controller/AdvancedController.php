<?php

namespace App\Application\Controller;

use App\Application\Response;
use App\Domain\Manager\MessageManager;
use App\Domain\Manager\SerializerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

final class AdvancedController extends AbstractController
{
    /**
     * @Route("/message")
     */
    public function message(MessageManager $messageManager): Response
    {
        return new Response([
            'data' => $messageManager->call(),
        ]);
    }

    /**
     * @Route("/serializer")
     */
    public function serializer(SerializerManager $serializerManager): Response
    {
        return new Response([
            'data' => $serializerManager->call(),
        ]);
    }
}
