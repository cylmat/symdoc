<?php

namespace App\Application\Listener;

use App\Application\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

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

        $dir = strtolower($match[1]);
        $ctrl = strtolower(strstr($match[2], 'Controller', true));
        $action = 'index' === $match[3] ? '' : '_'.$match[3];

        $templatePath = strtolower("$dir/".$ctrl."$action.html.twig");

        $response = $event->getResponse();
        if (!$response instanceof Response) {
            return;
        }

        /** @var Response $response */
        $content = $this->twig->render($templatePath, $response->getControllerData());
        $response->setContent($content);
    }
}