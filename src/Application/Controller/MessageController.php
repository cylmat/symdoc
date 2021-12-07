<?php

namespace App\Application\Controller;

use App\Domain\Manager\MessageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MessageController extends AbstractController
{
    /**
     * @Route("/message")
     */
    public function index(MessageManager $messageManager): Response
    {
        $messageManager->call();

        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }
}
