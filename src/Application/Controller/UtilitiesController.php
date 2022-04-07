<?php

namespace App\Application\Controller;

use App\Application\Response;
use App\Domain\Manager\ComponentManager;
use App\Domain\Manager\ExpressionManager;
use App\Domain\Manager\FormatManager;
use App\Domain\Manager\PhpManager;
use DateTime;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
     */
    public function httpclient(Request $request): Response
    {
        $url = 'http://localhost' . $this->generateUrl('app_application_utilities_httpclient');
        $response = HttpClient::create()->request(Request::METHOD_GET, $url);

        return new Response([
            'data' => [
                'response_headers' => $response->getHeaders(),
                'response' => $response,
            ],
            'current_date' => $this->getDateTime(),
        ]);
    }

    private function getDateTime(): string
    {
        return (new DateTime('now', new DateTimeZone(self::DATETIME_PARIS)))->format(DateTime::COOKIE);
    }

    /**
     * @Route("/expression")
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
