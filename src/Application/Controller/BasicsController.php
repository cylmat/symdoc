<?php

namespace App\Application\Controller;

use App\Application\FormCreator\FormCreator;
use App\Application\Response;
use App\Domain\Manager\RedisManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class BasicsController extends AbstractController
{
    private $bfc;

    public function __construct(BasicsFormController $bfc)
    {
        $this->bfc = $bfc;
    }

    /*
     * Don't use getRouteCollection in prod as it is slow!
     */
    /*
     * ROUTING: matching route with controller
     * @see (for fun) https://symfony.com/blog/new-in-symfony-3-2-unicode-routing-support
     *
     * $_route, $_controller, ... injected automatically by Sf
     * parameter<requirements>
     */
    /**
     * @Route("/routing/{slug<[[:alpha:]-]+>?}/{param?foo}/{def}/{last}",
     *  defaults={
     *      "def"="inside",
     *      "placehoster"="localhost"
     *  },
     *  host="{placehoster}",
     *  requirements={
     *      "placehoster"="localhost|docker"
     *  },
     *  options={
     *      "compiler_class": \Symfony\Component\Routing\RouteCompiler::class,
     *      "utf8": true,
     *  }
     * )
     */
    public function routing(
        Request $request,
        string $_route,
        string $_controller,
        string $def,
        ?string $slug = '',
        string $param = 'not_this_default',
        string $last = 'default_value'
    ): Response {
        return new Response([
            'data' => [
                $request,
                '_route' => $_route,
                '_controller' => $_controller,
                'slug' => $slug,
                'param' => $param,
                'def' => $def,
                'last' => $last,
            ]
        ]);
    }

    /**
     * @Route("/cache")
     */
    public function cache(RedisManager $redisManager): Response
    {
        return new Response([
            'data' => $redisManager->call(),
        ]);
    }

    /**
     * @Route("/doctrine")
     */
    public function doctrine(): Response
    {
        return new Response([]);
    }

    // Froms /////////////////////////////////////////////////////

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
}
