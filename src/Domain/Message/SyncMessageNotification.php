<?php

namespace App\Domain\Message;

class SyncMessageNotification implements MyMessageInterface
{
    private string $message;

    public function __construct(string $message)
    {
        $this->message = 'Instant synchro: ' . $message;
    }

    public function getContent(): string
    {
        return $this->message;
    }
}
