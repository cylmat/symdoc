<?php

namespace App\Application\Controller;

use App\Application\FormCreator\FormCreator;
use App\Application\Response;
use App\Domain\Manager\RedisManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Route groups:
 * @Route("", requirements={"_locale": "en|fr"}, name="")
 */
final class BasicsController extends AbstractController
{
    private $bfc;

    public function __construct(BasicsFormController $bfc)
    {
        $this->bfc = $bfc;
    }

    /**
     * @Route("/doctrine")
     */
    public function doctrine(): Response
    {
        return new Response([]);
    }

    // Forms /////////////////////////////////////////////////////
    /**
     * @Route("/form")
     */
    public function form(Request $request, FormCreator $formCreator): Response
    {
        return $this->bfc->form($request, $formCreator);
    }

    /**
     * @Route("/form-build")
     */
    public function formBuild(Request $request, FormCreator $formCreator): Response
    {
        return $this->bfc->formBuild($request, $formCreator);
    }
    // -forms

    /**
     * @Route("/cache")
     */
    public function cache(RedisManager $redisManager): Response
    {
        return new Response([
            'data' => $redisManager->call(),
        ]);
    }
}
