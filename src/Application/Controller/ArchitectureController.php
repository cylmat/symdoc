<?php

namespace App\Application\Controller;

use App\Application\Response;
use App\Domain\Manager\HeaderManager;
use DateTime;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class ArchitectureController extends AbstractController
{
    private const DATETIME_PARIS = 'Europe/Paris';

    /**
     * @Route("/request")
     */
    public function httprequest(Request $request, HeaderManager $headerManager): Response
    {
        return new Response([
            'controller_name' => 'HeaderController',
            'data' => array_merge(
                ['attributes' => $request->attributes],
                $headerManager->call()
            ),
            'current_date' => $this->getDateTime(),
        ]);
    }

    /**
     * @Route("/json")
     */
    public function jsonresponse(Request $request): JsonResponse
    {
        $response = new JsonResponse(['data' => 123]);
        $response->setData(['data' => 456]);

        $response2 = JsonResponse::fromJsonString(
            json_encode(['data' => 789])
        );

        $response3 = $this->json(['data' => 555]);

        return $response3;
    }

    private function getDateTime(): string
    {
        return (new DateTime('now', new DateTimeZone(self::DATETIME_PARIS)))->format(DateTime::COOKIE);
    }
}
