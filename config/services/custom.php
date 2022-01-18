<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Application\Service\DateTimeService;

return function (ContainerConfigurator $configurator) {
    //$configurator->import('services_config.php');

    /*
    $services
        ->load('App\\', '../src/*')
        ->exclude('../src/{DependencyInjection,Entity,Migrations,Tests,Kernel.php}');
    */

    // makes classes in src/ available to be used as services
    // this creates a service per class whose id is the fully-qualified class name
    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->set('mydatetimeservice', DateTimeService::class);
        /*->args([
            service(MessageGenerator::class),
            service('mailer'),
            'superadmin@example.com',
        ]);*/
        //->public()
        //->arg('$adminEmail', 'manager@example.com');
        //->args([service('logger')])
        //->arg('$logger', service('monolog.logger.request'))

    $services->set(DateTimeService::class)
        // the first argument is the class and the second argument is the static method
        ->factory([DateTimeService::class, 'createFactory']);
};
