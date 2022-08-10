<?php

namespace App\Domain\Manager\Advanced;

use App\Domain\Core\Interfaces\ManagerInterface;
use App\Domain\Message\MessageNotification;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessageManager implements ManagerInterface
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function call(array $context = []): array
    {
        // $this->dispatchMessage (in AbstractController)
        $msg = new MessageNotification('Message from the manager');
        $syncMsg = new MessageNotification('Instant message');

        return [
            'message' => $msg,
            'message_bus' => $this->messageBus,
            'envelope_dispatched' => $this->messageBus->dispatch($msg),
            'instant' => $this->messageBus->dispatch($syncMsg),
        ];
    }
}
