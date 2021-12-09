<?php

namespace App\Application\Controller;

use App\Domain\Manager\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CacheController extends AbstractController
{
    /**
     * @Route("/headers")
     */
    public function headers(Request $request, CacheManager $cacheManager): Response
    {
        return $this->render('cache/index.html.twig', [
            'controller_name' => 'CacheController',
            'data' => $cacheManager->call(),
        ]);
    }
}
