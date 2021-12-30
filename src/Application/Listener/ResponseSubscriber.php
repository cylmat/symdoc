<?php

namespace App\Application\Listener;

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
            KernelEvents::RESPONSE => 'getTemplate'
        ];
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
        if (!$response instanceof Response) {
            return;
        }

        /** @var Response $response */
        try {
            $content = $this->twig->render($customTemplate, $response->getControllerData());
        } catch (LoaderError $loaderError) {
            $defaultTemplate = "/layout.html.twig";
            $content = $this->twig->render($defaultTemplate, $response->getControllerData());
        }
        $response->setContent($content);
    }
}
