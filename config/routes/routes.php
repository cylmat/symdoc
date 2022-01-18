<?php

// config/routes.php
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('static', '/static ')
        // the controller value has the format [controller_class, method_name]
        ->controller([Symfony\Bundle\FrameworkBundle\Controller\TemplateController::class, 'templateAction'])

        ->defaults([
            'page' => 1,
            'template' => 'base.html.twig',
            'maxAge' => 86400,
            'sharedAge' => 86400,
        ])
        ->requirements(['page' => '\d+'])

        // if the action is implemented as the __invoke() method of the
        // controller class, you can skip the 'method_name' part:
        // ->controller(BlogController::class)
    ;
};
