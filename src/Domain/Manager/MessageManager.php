<?php

namespace App\Domain\Manager;

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

    public function call(): array
    {
        // $this->dispatchMessage in AbstractController
        $this->messageBus->dispatch(new Message('bouh'));

        return [];
    }
}