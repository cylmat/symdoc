<?php

namespace App\Application\Controller;

use App\Domain\Manager\MiscManager;
use KoenHoeijmakers\Headers\Header;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

final class MiscController extends AbstractController
{
    /**
     * @Route("/misc")
     */
    public function index(Request $request, MiscManager $miscManager): Response
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
     * @Route("/headers")
     */
    public function headersAction(Request $request, Environment $twig): Response
    {
        $response = new Response('hi', Response::HTTP_OK, [
            Header::ACCEPT_ENCODING,
        ]); 

        $response->setVary(Header::ACCEPT_ENCODING, true); // replace = true
        $response->setVary(Header::USER_AGENT); // replace = true

        $response->setMaxAge(100); //Sets the number of seconds after which the response should no longer be considered fresh.

        return $response;
    }
}
