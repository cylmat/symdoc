<?php

namespace App\Domain\Message;

class Message
{
    public $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }
}
