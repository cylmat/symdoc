<?php

namespace App\Application\DependencyInjection\Configurator;

use App\Application\Service\TwitterClient;

/*
 *  The service configurator is a feature of the service container
 *  that allows you to use a callable to configure a service after its instantiation.
 */
class TwitterServiceConfigurator
{
    public function __construct(string $customService = null)
    {
    }

    public function configuringService(TwitterClient $twitterClient)
    {
        $twitterClient->configuration = ['from configurator'];
    }
}
