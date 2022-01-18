<?php

namespace App\Application\Controller;

use App\Application\FormCreator\FormCreator;
use App\Application\Response;
use App\Domain\Manager\RedisManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

/*******************
 * Getting Started *
 ******************/

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
     * @Route("/routing/{slug<[[:alpha:]-]*>?}/{param?foo}/{!def}/{last}{_locale?en}",
     *  defaults={
     *      "def": "inside",
     *      "placehoster": "%devhost%",
     *      "_locale": "en"
     *  },
     *  host="{placehoster}",
     *  requirements={
     *      "placehoster": "localhost|docker",
     *      "_locale": "(en|fr)?",
     *      "_format": "html|xml"
     *  },
     *  options={
     *      "compiler_class": \Symfony\Component\Routing\RouteCompiler::class,
     *      "utf8": true,
     *  },
     *  schemes={"http"}
     * )
     */
    public function routing(
        Request $request,
        string $_route,
        string $_controller,
        //string $_format,
        //string $_fragment,
        string $def, // !def will always be included
        string $_locale = 'en',
        ?string $slug = '',
        string $param = 'not_this_default',
        string $last = 'default_value'
    ): Response {
        $pathInfo = $request->getPathInfo();

        try {
            $url = $this->generateUrl('app_application_basics_routing', [
                '_locale' => 'en', UrlGeneratorInterface::ABSOLUTE_URL,
            ]);
        } catch (RouteNotFoundException $e) {
            $url = false;
        }

        return new Response([
            'data' => [
                $request,
                '_route' => $_route,
                '_controller' => $_controller,
               // '_format' => $_format,
               // '_fragment' => $_fragment,
                '_locale' => $_locale,
                'slug' => $slug,
                'param' => $param,
                'def' => $def,
                'last' => $last,
                'url' => $url,
            ]
        ]);
    }

    /*
     * @Route({
     *      "en": "/routing-convert/{id}",
     *      "fr": "/ma-route"/{id}"
     * }, defaults={"id": null})
     *
     * Need to use the ParamConverter!
     * @see https://symfony.com/bundles/SensioFrameworkExtraBundle/current/annotations/converters.html
     *
    public function routingParamConverter(User $user = null)
    {
    }
     */

/**************
 * The Basics *
 *************/

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
