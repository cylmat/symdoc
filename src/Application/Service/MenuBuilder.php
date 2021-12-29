<?php

namespace App\Application\Service;

use Bundle\ExtBundleController;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Routing\RouterInterface;

final class MenuBuilder
{
    private $factory;
    private $routeCollection;

    private const GROUPS = [
        'Advanced' => false,
        'Architecture' => false,
        'Basics' => false,
        'Frontend' => false,
        'Production' => false,
        'Security' => false,
        'Started' => false,
        'Utilities' => false,
    ];

    private const LABELS = [
        'primary', 'secondary', 'success', 'danger',
        'warning', 'info', 'light', 'dark', 99 => ''
    ];

    public function __construct(FactoryInterface $factory, RouterInterface $router)
    {
        $this->factory = $factory;
        $this->routeCollection = $router->getRouteCollection();
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        foreach ($this->filterRoutes() as $route => $data) {
            $label = array_search($data->group, array_keys(self::GROUPS));
            $label = false !== $label ? self::LABELS[$label] : self::LABELS[99];
            $menu->addChild($data->controller, [
                'route' => $route,
                'linkAttributes' => ['class' => "p-1 alert-$label"]
            ]);
        }

        /**
         * ExtBundle Phpext if exists
         */
        if (ExtBundleController::isAccessible()) {
            $menu->addChild('Phpext', [
                'route' => 'bundle_phpext'
            ]);
        }

        return $menu;
    }

    private function filterRoutes(): iterable
    {
        $routes = array_filter($this->routeCollection->all(), function ($object, $route) {
            if (0 === strpos($route, 'app_')) {
                return true;
            }
        }, ARRAY_FILTER_USE_BOTH);

        $routes = array_map(function ($object) {
            preg_match('/\\\\(\w+)Controller::(\w+)/', $object->getDefaults()['_controller'], $match);

            $group = $match[1];
            $action = $match[2];

            return (object)[
                'group' => $group,
                'path' => $object->getPath(),
                'controller' => ucfirst($action),
            ];
        }, $routes);

        foreach ($routes as $path => $data) {
            yield $path => $data;
        }
    }

    public function createDocMenu(array $options): ItemInterface
    {
        $symdoc = 'https://symfony.com/doc/current/index.html';

        $menu = $this->factory->createItem('root');
        foreach ($this->getDocLinks() as $doc => $validated) {
            $validated = $validated ? 'validated' : '';
            $label = self::LABELS[array_search($doc, array_keys(self::GROUPS))];
            $menu->addChild($doc, [
                'uri' => $symdoc,
                "linkAttributes" => ["class" => "alert-$label $validated"]
            ]);
        }

        return $menu;
    }

    private function getDocLinks(): array
    {
        return self::GROUPS;
    }
}
