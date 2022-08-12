<?php

namespace App\Domain\Manager\Advanced;

use App\Domain\Core\Interfaces\ManagerInterface;
use App\Domain\Notification\BrowserNotification;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

final class NotificationManager implements ManagerInterface
{
    private NotifierInterface $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    // composer require symfony/notifier
    // for email composer require symfony/twig-pack twig/cssinliner-extra twig/inky-extra (inky_to_html)
    public function call(array $context = []): array
    {
        $notification = (new BrowserNotification('subject', ['browser'])) // ChannelInterface
            ->content('New price for you')
            ->importance(Notification::IMPORTANCE_LOW);

        $recipient = new Recipient('recipient-email');

        return [
            'notification' => $notification,
            'sended' => $this->notifier->send($notification, $recipient),
        ];
    }
}
