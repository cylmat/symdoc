<?php

namespace App\Domain\Manager\Advanced;

use App\Domain\Core\Interfaces\ManagerInterface;
use App\Domain\Message\Message;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessageManager implements ManagerInterface
{
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function call(array $context = []): array
    {
        // $this->dispatchMessage (in AbstractController)
        $msg = new Message('Message from manager');

        return [
            'message_dispatched' => $this->messageBus->dispatch($msg),
            'message' => $msg,
        ];
    }
}
