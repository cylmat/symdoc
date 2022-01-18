<?php

namespace App\Application\Service\Route;

use Symfony\Bundle\FrameworkBundle\Routing\RouteLoaderInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

class CustomRouteLoader implements RouteLoaderInterface
{
    public function loadMyRoutes(): RouteCollection
    {
        $collection = new RouteCollection();
        $collection->add('from_custom', new Route('app_application_basics_routing'));

        return $collection;
    }
}
