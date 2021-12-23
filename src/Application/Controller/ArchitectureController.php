<?php

namespace App\Application\Controller;

use App\Application\Response;
use App\Domain\Manager\HeaderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    private function getDateTime(): string
    {
        return (new \DateTime('now', new \DateTimeZone(self::DATETIME_PARIS)))->format(\DateTime::COOKIE);
    }
}
