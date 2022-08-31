<?php

namespace App\Application\Controller;

use App\Application\Response;
use App\Domain\Manager\Utilities\ComponentManager;
use App\Domain\Manager\Utilities\ExpressionManager;
use App\Domain\Manager\FormatManager;
use App\Domain\Manager\PhpManager;
use App\Domain\Manager\Utilities\HttpClientManager;
use DateTime;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class UtilitiesController extends AbstractController
{
    private const DATETIME_PARIS = 'Europe/Paris';

    private $componentController;

    public function __construct(UtilitiesComponentController $componentController)
    {
        $this->componentController = $componentController;
    }

    /**
     * @Route("/http-client")
     *
     * Can use Symfony Contracts, PSR-18, HTTPlug v1/v2 and native PHP streams
     */
    public function httpclient(HttpClientManager $manager, Request $request, HttpClientInterface $httpClient): Response
    {
        $host = $request->server->get('SYMFONY_PROJECT_DEFAULT_ROUTE_URL');
        $url = $host . $this->generateUrl('app_application_utilities_expression');

        $response = $manager->call()['response'];

        return new Response([
            'data' => [
                'response_headers' => $response->getHeaders(),
                'response' => $response,
            ],
            'current_date' => $this->getDateTime(),
            'start time' => $response->getInfo('start_time'),

            // returns detailed logs about the requests and responses of the HTTP transaction
            'logs' => $response->getInfo('debug'),
        ]);
    }

    private function getDateTime(): string
    {
        return (new DateTime('now', new DateTimeZone(self::DATETIME_PARIS)))->format(DateTime::COOKIE);
    }

    /**
     * @Route("/expression", name="app_application_utilities_expression")
     */
    public function expression(ExpressionManager $expressionManager): Response
    {
        return new Response([
            'data' => $expressionManager->call(),
        ]);
    }

    /**
     * @Route("/format")
     */
    public function format(FormatManager $formatManager): Response
    {
        return new Response([
            'data' => $formatManager->call(),
        ]);
    }

    /**
     * @Route("/php")
     */
    public function phpNoMenu(PhpManager $phpManager): Response
    {
        return new Response([
            'data' => $phpManager->call()
        ]);
    }

    /******************************
     * Components
     ******************************/

    /**
     * @Route("/components/{name}")
     */
    public function components(ComponentManager $miscManager, ?string $name = null): Response
    {
        $this->getUser();
        $ctx = $name ? ['name' => $name] : [];

        return $this->componentController->outsidecalled($miscManager, $ctx);
    }
}
