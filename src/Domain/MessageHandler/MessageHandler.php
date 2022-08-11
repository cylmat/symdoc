<?php

namespace App\Domain\MessageHandler;

use App\Domain\Message\MessageNotification;
use App\Domain\Message\MyMessageInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

class MessageHandler implements MessageHandlerInterface, MessageSubscriberInterface
{
    public function __invoke(MyMessageInterface $message)
    {
        echo $message->getContent() . ' -:- ' . PHP_EOL;
    }

    public static function getHandledMessages(): iterable
    {
        yield MessageNotification::class => [
            'from_transport' => 'my_async',
        ];
    }
}
