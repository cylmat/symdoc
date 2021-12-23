<?php

namespace App\Application\Controller\Utilities;

use App\Domain\Manager\HeaderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HttpClientController extends AbstractController
{
    private const DATETIME_PARIS = 'Europe/Paris';

    /**
     * @Route("/http-client")
     */
    public function httpclient(Request $request): Response
    {
        $response = HttpClient::create()->request(Request::METHOD_GET, 'http://localhost:88/headers');

        return $this->render('all/headers.html.twig', [
            'controller_name' => 'HeaderController',
            'data' => [
                'response_headers' => $response->getHeaders(),
                'response' => $response,
            ],
            'current_date' => $this->getDateTime(),
        ]);
    }

    private function getDateTime(): string
    {
        return (new \DateTime('now', new \DateTimeZone(self::DATETIME_PARIS)))->format(\DateTime::COOKIE);
    }
}
