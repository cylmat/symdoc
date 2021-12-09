<?php

namespace App\Application\Service;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Routing\RouterInterface;

class KnpMenuBuilder
{
    private $factory;
    private $routeCollection;

    public function __construct(FactoryInterface $factory, RouterInterface $router)
    {
        $this->factory = $factory;
        $this->routeCollection = $router->getRouteCollection();
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        foreach ($this->filterRoutes() as $route => $controller) {
            $menu->addChild($controller, ['route' => $route]);
        }

        return $menu;
    }

    private function filterRoutes(): \Generator
    {
        $routes = array_filter($this->routeCollection->all(), function($object, $route) {
            if (0 === strpos($route, 'app_')) {
                return true;
            }
        }, ARRAY_FILTER_USE_BOTH);
        
        $routes = array_map(function($object) {
            preg_match('/\\\\(\w+)Controller::(\w+)/', $object->getDefaults()['_controller'], $match);

            return [
                'path' => $object->getPath(),
                'controller' => $match[1].'::'.$match[2],
            ];
        }, $routes);

        foreach($routes as $path => $route) {
            yield $path => $route['controller'];
        }
    }
}