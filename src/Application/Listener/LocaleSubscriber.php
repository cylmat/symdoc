<?php

namespace App\Application\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    private string $defaultLocale;

    public function __construct(string $defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        // try to see if the locale has been set as a _locale routing parameter
        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        } else {
            // if no explicit locale has been set on this request, use one from the session
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }

        if (false !== \strpos($request->attributes->get('_controller'), 'translation')) {
            $request->setDefaultLocale('al');
            $request->setLocale('al');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            // must be registered after (i.e. with a lower priority than)
            // the default Locale listener and Session
            KernelEvents::REQUEST => [
                ['onKernelRequest', 0]
            ],
        ];
    }
}
