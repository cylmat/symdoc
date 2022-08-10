<?php

namespace App\Domain\Message;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

// tags: [messenger.message_handler]
class MessageHandler implements MessageHandlerInterface
{
    public function __invoke(MessageNotification $message): string
    {
        return $message->getContent() . ' handled!';
    }
}
