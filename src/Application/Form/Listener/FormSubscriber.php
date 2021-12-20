<?php 

namespace App\Application\Form\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;

class FormSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => "preSubmit",
            FormEvents::SUBMIT => "submit",
            FormEvents::POST_SUBMIT => "postSubmit",
        ];
    }

    public function preSubmit(string $event)
    {
        d($event);
    }

    public function submit(string $event)
    {

    }

    public function postSubmit(string $event)
    {

    }
}