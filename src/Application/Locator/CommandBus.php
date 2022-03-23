<?php

namespace App\Application\Locator;

use App\Application\Service\Rot13Transformer;
use App\Application\Service\TwitterClient;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

/**
 * @see https://symfony.com/doc/5.0/service_container/service_subscribers_locators.html
 *
 * Service Subscribers are intended to solve this problem by giving access to a set of predefined services
 * while instantiating them only when actually needed through a Service Locator
 * without being sure that all of them will actually be used.
 *
 * Autoconfigure or container.service_subscriber tag
 */
class CommandBus implements ServiceSubscriberInterface
{
    private $locator;

    public function __construct(ContainerInterface $locator)
    {
        $this->locator = $locator;
    }

    public static function getSubscribedServices()
    {
        return [
            TwitterClient::class => TwitterClient::class,
            Rot13Transformer::class => Rot13Transformer::class,
            '?' . LoggerInterface::class, // optional
        ];
    }

    public function handle(string $command)
    {
        if ($this->locator->has($command)) {
            $this->locator->get($command);

            // ... do stuff ...
        }
    }
}
