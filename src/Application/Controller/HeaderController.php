<?php

namespace App\Application\Controller;

use App\Domain\Manager\HeaderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HeaderController extends AbstractController
{
    private const DATETIME_PARIS = 'Europe/Paris';

    /**
     * @Route("/headers")
     */
    public function headers(Request $request, HeaderManager $cacheManager): Response
    {
        return $this->render('header/index.html.twig', [
            'controller_name' => 'HeaderController',
            'data' => $cacheManager->call(),
            'current_date' => (new \DateTime('now', new \DateTimeZone(self::DATETIME_PARIS)))->format(\DateTime::COOKIE),
        ]);
    }

    /**
     * @Route("/request")
     */
    public function request(Request $request): Response
    {
        $response = HttpClient::create()->request(Request::METHOD_GET, 'http://localhost:88/headers');

        return $this->render('header/index.html.twig', [
            'controller_name' => 'HeaderController',
            'data' => [
                'headers' => $response->getHeaders(),
                'response' => $response,
            ],
            'current_date' => (new \DateTime('now', new \DateTimeZone(self::DATETIME_PARIS)))->format(\DateTime::COOKIE),
        ]);
    }
}
