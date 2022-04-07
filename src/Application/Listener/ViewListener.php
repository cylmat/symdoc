<?php

namespace App\Application\Listener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ViewListener
{
    // public function onKernelResponse(ResponseEvent $event)
    public function __invoke(ResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            // don't do anything if it's not the master request
            return;
        }

        $event->setResponse($event->getResponse()->setCharset('UTF-8'));
    }
}
