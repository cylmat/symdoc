<?php

namespace App\Application\Listener;

use App\Application\Event\CustomEvent;
use App\Application\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;
use Twig\Error\LoaderError;

class ResponseSubscriber implements EventSubscriberInterface
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // KernelEvents::RESPONSE => 'getTemplate',
            // or
            ResponseEvent::class => 'getTemplateFqcn',

            // can be extended with AddEventAliasesPass
            'my_custom::class' => 'myCustomEvent',

            // custom event
            CustomEvent::class . 'before_called' => 'tryitBefore',
            CustomEvent::class . 'after' => 'tryitAfter',
        ];
    }

    public function myCustomEvent(ResponseEvent $event)
    {
    }

    // Fully Qualified Class Name
    public function getTemplateFqcn(ResponseEvent $event): void
    {
        $this->getTemplate($event);
        $ctrl = $event->getRequest()->attributes->get('_controller');

        if (false !== strpos($ctrl, 'ArchitectureController::event')) {
            $event->getResponse()->headers->add(['X-FROM' => 'ArchitectureCtrl from response-subscriber']);
        }
    }

    public function getTemplate(ResponseEvent $event): void
    {
        $route = $event->getRequest()->attributes->get('_controller');
        if (!preg_match("/\\\\(\w+)\\\\(\w+)::(\w+)$/", $route, $match)) {
            return;
        }
        $ctrl = strtolower(strstr($match[2], 'Controller', true));
        $action = 'index' === $match[3] ? '' : $match[3];

        $customTemplate = strtolower($ctrl . "/$action.html.twig");

        $response = $event->getResponse();
        $response->headers->add(['X-TEMPLATE' => $customTemplate]);
        if (!$response instanceof Response) {
            return;
        }

        /** @var Response $response */
        try {
            $content = $this->twig->render($customTemplate, $response->getControllerData());
        } catch (LoaderError $loaderError) {
            $response->headers->add(['X-ERROR' => $loaderError->getMessage()]);
            $defaultTemplate = "/layout.html.twig";
            $content = $this->twig->render($defaultTemplate, $response->getControllerData());
        }
        $response->setContent($content);
    }

    public function tryitBefore(CustomEvent $event)
    {
        $event->setValue('"before" subscriber');
    }

    public function tryitAfter(CustomEvent $event)
    {
        $event->setValue('"after" subscriber');
    }
}
