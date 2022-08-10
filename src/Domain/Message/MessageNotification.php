<?php

namespace App\Domain\Message;

class MessageNotification
{
    private string $message;

    /**
     * try to pass only scalar, to use later
     *   as it will be serialized and delayed
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function getContent(): string
    {
        return $this->message;
    }
}
