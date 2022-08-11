<?php

namespace App\Domain\Message;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Retry\RetryStrategyInterface;

class RetryMessaging implements RetryStrategyInterface
{
    public function isRetryable(Envelope $message): bool
    {
        return false;
    }

    public function getWaitingTime(Envelope $message): int
    {
        return 1;
    }
}
