<?php

namespace App\Application\Controller\Architecture;

use App\Domain\Manager\HeaderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HttpController extends AbstractController
{
    /**
     * @Route("/request")
     */
    public function request(Request $request, HeaderManager $headerManager): Response
    {
        return $this->render('all/headers.html.twig', [
            'controller_name' => 'HeaderController',
            'data' => array_merge(
                ['attributes' => $request->attributes], 
                $headerManager->call()
            ),
            'current_date' => $this->getDateTime(),
        ]);
    }
}
