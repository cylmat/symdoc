<?php

namespace App\Application\Controller;

use App\Domain\Manager\AllManager;
use KoenHoeijmakers\Headers\Header;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class AllController extends AbstractController
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/all")
     */
    public function index(AllManager $allManager): Response
    {
        return $this->render('all/index.html.twig', [
            'controller_name' => 'AllController',
            'data' => $allManager->call(),
        ]);
    }

    /**
     * @Route("/headers")
     */
    public function headersAction(Request $request): Response
    {
        $response = new Response();

        $response->headers->set(Header::VARY, ['Accept-Encoding', 'User-Agent'], true); //replace

        $response->setVary(Header::ACCEPT_ENCODING, true); // replace = true
        $response->setVary(Header::USER_AGENT);

        $response->setVary(['Accept-Encoding', 'User-Agent']); // replace = true
        $response->setMaxAge(100); //Sets the number of seconds after which the response should no longer be considered fresh.

        $response = $this->render('all/index.html.twig', [
            'controller_name' => 'AllController',
            'data' => $response->headers->all(),
        ]);

        /*$response = new Response('<hi>headers ok</hi>', Response::HTTP_OK, [
            Header::ACCEPT_ENCODING,
        ]); */

        return $response;
    }
}
