<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function(ContainerConfigurator $configurator) {
    $configurator->import('services_config.php');

    /*
    // If you want to import a whole directory:
    // $configurator->import('services/');

    $services = $configurator->services()
        ->defaults()
            ->autowire()
            ->autoconfigure()
    ;

    $services
      ->load('App\\', '../src/*')
        ->exclude('../src/{DependencyInjection,Entity,Migrations,Tests,Kernel.php}');
    */
};