<?php

namespace App\Application\Controller;

use App\Application\Response;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\WebLink\Link;
use Twig\Environment;

/**
 * Route groups:
 * @Route("", requirements={"_locale": "en|fr"}, name="")
 */
final class StartedController extends AbstractController
{
    /**
     * ContainerBag can inject lot of params instead of one by one
     */
    public function __construct(object $from_php_service_logger, ContainerBagInterface $alpha)
    {
        $alpha->get('app_env');
    }

    /**
     * @Route("/controller")
     */
    public function controller(
        Request $request,
        SessionInterface $session,
        object $bind_from_service_logger, //binded from service.yaml
        string $my_custom_value_resolver // autoload from CustomValueResolver
    ) {
        // Container
        $param = $this->getParameter('devhost');
        $subscribedServices = self::getSubscribedServices();
        $service = null;
        /*if ($this->has('twitter_client')) {
            $service = $this->get('twitter_client');
        }*/

        // Routes
        $url = $this->generateUrl('contact', ['param'], UrlGeneratorInterface::ABSOLUTE_PATH);

        // Response
        // $this->forward(self::class.'::routing', ['path']);
        $redirectResponse = $this->redirect('https://userland.com', 302);
        $redirectRouteResponse = $this->redirectToRoute('contact', ['param'], 302);
        // use serializer service, or json_encode
        $jsonResponse = $this->json(['data'], 200, ['headers'], ['context']);
        // $binaryFileResponse = $this->file('file', 'filename', ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        // $html = $this->renderView('template.html.twig', ['param']);
        // $html = $this->render('template.html.twig', ['param'], new Response());
        $streamedResponse = $this->stream('template.html.twig', ['param'], new StreamedResponse());

        // Exception
        $notfound = $this->createNotFoundException('Not Found!', new NotFoundHttpException('Not found!'));
        $denied = $this->createAccessDeniedException('Access Denied!', new AccessDeniedException('path'));

        // Form
        // $form = $this->createForm('MyFormType::class', (object)['entity'], ['options']);
        // $formBuilder = $this->createFormBuilder((object)['entity'], ['options']);

        // User & security
        $isGranted = $this->isGranted('attibutes', 'subject');
        // $this->denyAccessUnlessGranted('attributes', 'subject', 'Access Denied!');
        $user = $this->getUser(); // $this->container->get('security.token_storage')
        $managerRegistry = $this->getDoctrine();
        $csrfValid = $this->isCsrfTokenValid('id', '123token');
        //$envelopeMessage = $this->dispatchMessage('My message', ['stamps']);

        // Flash and link
        $this->addFlash('INFO', 'my message'); // session

        $request = new Request();
        $this->addLink($request, new Link()); // symfony/web-link

        return new Response([
            'data' => [
                'param' => $param,
                'subscribed' => $subscribedServices,
                'service' => $service,
                'url' => $url,
                'redirect' => $redirectResponse,
                'redirectRoute' => $redirectRouteResponse,
                'json' => $jsonResponse,
                'stream' => $streamedResponse,
                'notfound' => $notfound,
                'denied' => $denied,
                'isGranted' => $isGranted,
                'user' => $user,
                'manager' => $managerRegistry,
                'csrfValid' => $csrfValid,
                'response link' => $request->attributes,

                // {% messages in app.flashes(['success', 'warning']) %}
                '_retrieve_from_twig' => $this->container->get('session')->getFlashBag(),
            ]
        ]);
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
     * @Route("/routing/{slug<[[:alpha:]-]*>?slug}/{param?par}/{!def}/{last}{_locale?en}",
     *  defaults={
     *      "def": "defval",
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

    /**
     * @Route("/templating")
     * lint:twig ./templates --show-deprecations
     * debug:twig --filter=date
     * composer require symfony/var-dumper
     */
    public function templating(Environment $twig)
    {
        /*
         * order:
            $foo['bar'] (array and element);
            $foo->bar (object and public property);
            $foo->bar() (object and public method);
            $foo->getBar() (object and getter method);
            $foo->isBar() (object and isser method);
            $foo->hasBar() (object and hasser method);
        */
        $loader = $twig->getLoader();
        $loader->exists('theme/layout_responsive.html.twig');

        // can change header for ESI rendering
        // $response->setPublic();
        return new Response([
            'data' => [
                'notifObject' => new class {
                    public function getNotifications()
                    {
                        return [
                            'hello', "i'm fine", 'bye'
                        ];
                    }
                },
                'appVariable' => new AppVariable(),
                'environment' => $twig,
                'loader' => $loader,
            ]
        ]);
    }

    public function embedded($list = 3)
    {
        return new HttpFoundationResponse("from_embedded");
    }
}
