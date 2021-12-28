<?php

use Bundle\ExtBundleController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('bundle_phpext', '/phpext')
        ->controller(ExtBundleController::class);
};