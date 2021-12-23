<?php

namespace App\Application\Controller\Advanced;

use App\Application\Response;
use App\Domain\Manager\MessageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

final class MessageController extends AbstractController
{
    /**
     * @Route("/message")
     */
    public function index(MessageManager $messageManager): Response
    {
        return new Response([
            'data' => $messageManager->call(),
        ]);
    }
}
