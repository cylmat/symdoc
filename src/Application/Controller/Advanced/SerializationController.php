<?php

namespace App\Application\Controller\Advanced;

use App\Application\Response;
use App\Domain\Manager\SerializerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

final class SerializationController extends AbstractController
{
    /**
     * @Route("/serializer")
     */
    public function index(SerializerManager $serializerManager): Response
    {
        return new Response([
            'data' => $serializerManager->call(),
        ]);
    }
}
