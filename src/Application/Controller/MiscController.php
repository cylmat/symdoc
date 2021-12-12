<?php

namespace App\Application\Controller;

use App\Domain\Manager\MessageManager;
use App\Domain\Manager\MiscManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class MiscController extends AbstractController
{
    /**
     * @Route("/misc")
     */
    public function misc(MiscManager $miscManager): Response
    {
        return $this->render('misc/index.html.twig', [
            'controller_name' => 'MiscController',
            'data' => $miscManager->call(),
        ]);
    }

    /**
     * @Route("/form")
     */
    public function form(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $data[0] = $request->attributes;

        return $this->render('misc/index.html.twig', [
            'controller_name' => 'MiscController',
            'data' => $data,
        ]);
    }

    /**
     * @Route("/message")
     */
    public function message(MessageManager $messageManager): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
            'data' => $messageManager->call(),
        ]);
    }
}
