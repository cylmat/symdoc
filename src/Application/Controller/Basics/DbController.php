<?php

namespace App\Application\Controller\Basics;

use App\Application\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

final class DbController extends AbstractController
{
    /**
     * @Route("/doctrine")
     */
    public function doctrine(): Response
    {
        return new Response([]);
    }
}
