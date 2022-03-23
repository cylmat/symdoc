<?php

namespace App\Application\Locator;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

class CommandWithTrait implements ServiceSubscriberInterface
{
    use ServiceSubscriberTrait;

    public function logger(): LoggerInterface
    {
        return $this->container->get(__METHOD__);
    }
}
