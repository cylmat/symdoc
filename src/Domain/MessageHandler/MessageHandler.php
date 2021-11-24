<?php

namespace App\Domain\MessageHandler;

use App\Domain\Message\Message;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class MessageHandler implements MessageHandlerInterface
{
    public function __invoke(Message $message)
    {
        echo $message->message;
    }
}
