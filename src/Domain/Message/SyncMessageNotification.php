<?php

namespace App\Domain\Message;

class SyncMessageNotification
{
    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function getContent(): string
    {
        return $this->message;
    }
}
