<?php

namespace App\Application\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

class CustomLoader extends Loader
{
    private $isLoaded = false;

    public function load($resource, string $type = null)
    {
        if (true === $this->isLoaded) {
            throw new \RuntimeException('Do not add the "extra" loader twice');
        }

        $path = '/my_extra/{parameter?}/{def?val}';
        $defaults = [
            '_controller' => 'App\Application\Controller\BasicsController::routing',
            'def' => 'default',
        ];
        $requirements = [
            'parameter' => '\d+',
        ];
        $route = new Route($path, $defaults, $requirements);

        $routes = new RouteCollection();
        $routes->add('my_extra_loader', $route);

        $this->isLoaded = true;

        return $routes;
    }

    public function supports($resource, string $type = null)
    {
        return 'my_extra' === $type;
    }
}
