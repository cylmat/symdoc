<?php

namespace App\Domain\Message\Middleware;

use App\Domain\Message\SyncMessageNotification;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class CustomMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (!$envelope->getMessage() instanceof SyncMessageNotification) {
            $envelope = $envelope->with(new HandledStamp('alpha-middleware', 'name'));
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
