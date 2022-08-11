<?php

namespace App\Domain\Message;

class AutoMessageNotification implements MyMessageInterface
{
    private string $message;

    /**
     * try to pass only scalar, to use later
     *   as it will be serialized and delayed
     */
    public function __construct(string $message)
    {
        $this->message = $message . (new \DateTime())->format(\DateTime::ATOM);
    }

    public function getContent(): string
    {
        return 'Auto : ' . $this->message;
    }
}
