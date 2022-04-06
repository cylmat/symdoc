<?php

namespace App\Application\Controller;

use App\Application\Locator\CommandBus;
use App\Application\Locator\CommandWithTrait;
use App\Application\Response;
use App\Application\Service\FromFactoryService;
use App\Application\Service\Rot13Transformer;
use App\Application\Service\TwitterClient;
use App\Domain\Manager\HeaderManager;
use DateTime;
use DateTimeZone;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\{ParamConverter, Template};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Annotation\Route;

final class ArchitectureController extends AbstractController
{
    private const DATETIME_PARIS = 'Europe/Paris';

    /**
     * @Route("/servicesdi")
     *
     * # Same LoggerInterface, different service
     *
     * # Action injector
     * # must be tagged with "controller.service_arguments"
     */
    public function servicesDi(
        LoggerInterface $logger,
        LoggerInterface $httpClientLogger,
        FromFactoryService $fromFactory,
        TwitterClient $twitterClient,
        Rot13Transformer $rot13,
        CommandBus $commandBus,
        string $myCustomData,
        iterable $rules,
        /** @var ServiceLocator[] $locators */
        ServiceLocator $taggedLocator,
        ServiceLocator $commandLocator,
        CommandWithTrait $commandWithTrait
    ): Response {
        return new Response([
            'data' => [
                'logger' => $logger,
                'httpClientLogger' => $httpClientLogger,
                'fromFactory' => $fromFactory,
                'serviceTwitter' => $twitterClient,
                'rot13' => $rot13,
                'commandBus' => $commandBus,
                'myCustomData' => $myCustomData,
                'rules' => $rules,
                'taggedLocator' => $taggedLocator,
                'taggedLocator.get.twitter' => $taggedLocator->get('customing_two_twitter'),
                'commandLocator' => $commandLocator,
                'commandWithTrait.logger' => $commandWithTrait->logger(),
            ]
        ]);
    }

    /**
     * @Route("/httpkernel")
     */
    /*
     * composer require symfony/http-kernel
     * bin/console debug:event-dispatcher kernel.controller_arguments
     * @see https://symfony.com/doc/5.0/reference/events.html
     *
     * HttpKernel::handle(Request $request, int $type = HttpKernelInterface::MASTER_REQUEST, bool $catch = true)
     *
     * kernel.request: Add more information to Request, initialize the system, return Response (e.g. add security layer)
     *  -> RouterListener
     * kernel.controller: Initialize things or change the controller just before the controller is executed.
     *  -> ControllerResolverInterface (e.g. FooBundle:Default:index format)
     *  -> collecting profiler data
     * kernel.controller_arguments
     *  -> @ParamConverter
     *  -> ArgumentResolverInterface
     * kernel.view: Transform a non-Response return value from a controller into a Response
     *  -> propagation is stopped
     *  -> @Template
     * kernel.response: Modify the Response object just before it is sent
     *  ->  listener modify the Response, modifying headers, adding cookies, injecting some JavaScript...
     * kernel.finish_request: Reset the global state of the application
     * kernel.terminate: To perform some "heavy" action after the response has been streamed to the user
     *  -> TerminableInterface
     * kernel.exception
     */
    public function httpkernel(Request $request, HeaderManager $headerManager): Response
    {
        $kernel = new HttpKernel(
            new EventDispatcher(),
            new ControllerResolver(),
            new RequestStack(),
            new ArgumentResolver()
        );

        return new Response([
            'data' => [
                'attributes' => $request->attributes,
                'headers' => $headerManager->call(),
                'kernel' => $kernel,
                'current_date' => $this->getDateTime(),
            ],
        ]);
    }

    /**
     * @Route("/json")
     */
    public function jsonresponseNomenu(Request $request): JsonResponse
    {
        $response = new JsonResponse(['data' => 123]);
        $response->setData(['data' => 456]);

        $response2 = JsonResponse::fromJsonString(
            json_encode(['data' => 789])
        );

        $response3 = $this->json(['data' => 555]);

        return $response3;
    }

    private function getDateTime(): string
    {
        return (new DateTime('now', new DateTimeZone(self::DATETIME_PARIS)))->format(DateTime::COOKIE);
    }
}
