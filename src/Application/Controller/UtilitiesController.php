<?php

namespace App\Application\Controller;

use App\Application\Response;
use App\Domain\Manager\ComponentManager;
use App\Domain\Manager\ExpressionManager;
use App\Domain\Manager\FormatManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class UtilitiesController extends AbstractController
{
    private const DATETIME_PARIS = 'Europe/Paris';

    private $componentController;

    public function __construct(ComponentController $componentController)
    {
        $this->componentController = $componentController;
    }

    /**
     * @Route("/http-client")
     */
    public function index(Request $request): Response
    {
        $response = HttpClient::create()->request(Request::METHOD_GET, 'http://localhost:88/headers');

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
        return (new \DateTime('now', new \DateTimeZone(self::DATETIME_PARIS)))->format(\DateTime::COOKIE);
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

    /*
     * Components
     */

    /**
     * @Route("/components")
     */
    public function components(ComponentManager $miscManager): Response
    {
        return $this->componentController->index($miscManager);
    }
}
