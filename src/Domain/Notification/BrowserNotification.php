<?php

namespace App\Domain\Notification;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

class BrowserNotification extends Notification //implements ChatNotificationInterface
{
    public function getChannels(Recipient $recipient): array
    {
        // .. logic
        #return $this->channels;
        return ['browser'];
    }
}
