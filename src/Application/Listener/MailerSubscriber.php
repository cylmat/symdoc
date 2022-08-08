<?php

namespace App\Application\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Event\MessageEvent;

class MailerSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            MessageEvent::class => 'setFromAddress'
        ];
    }

    public function setFromAddress(MessageEvent $event)
    {
        $event->getEnvelope()->getSender();
    }
}
