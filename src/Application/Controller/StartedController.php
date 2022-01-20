<?php

namespace App\Application\Controller;

use App\Application\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\WebLink\Link;

/**
 * Route groups:
 * @Route("", requirements={"_locale": "en|fr"}, name="")
 */
final class StartedController extends AbstractController
{
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
     * @Route("/controller")
     */
    public function controller()
    {
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
        $jsonResponse = $this->json(['data'], 200, ['headers'], ['context']);
        // $binaryFileResponse = $this->file('file', 'filename', ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        $this->addFlash('INFO', 'my message'); // session

        // $html = $this->renderView('template.html.twig', ['param']);
        // $html = $this->render('template.html.twig', ['param'], new Response());
        $streamedResponse = $this->stream('template.html.twig', ['param'], new StreamedResponse());

        // Exception
        $notfound = $this->createNotFoundException('Not Found!', new NotFoundHttpException());
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
                'flash' => $this->container->get('session')->getFlashBag(),
                'stream' => $streamedResponse,
                'notfound' => $notfound,
                'denied' => $denied,
                'isGranted' => $isGranted,
                'user' => $user,
                'manager' => $managerRegistry,
                'csrfValid' => $csrfValid,
                'response link' => $request->attributes,
            ]
        ]);
    }
}
