<?php

/**
 * composer require
 * symfony/config
 * symfony/http-kernel
 * symfony/http-foundation
 * symfony/routing
 * symfony/dependency-injection
 * symfony/framework-bundle
 *
 * @see https://symfony.com/doc/5.0/configuration/micro_kernel_trait.html
 */

namespace App;

// index.php
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

// require __DIR__.'/vendor/autoload.php';

class MicroKernelSample extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles()
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle()
        ];
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        // PHP equivalent of config/packages/framework.yaml
        $c->loadFromExtension('framework', [
            'secret' => 'S0ME_SECRET'
        ]);
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        // kernel is a service that points to this class
        // optional 3rd argument is the route name
        $routes->add('/random/{limit}', 'kernel::randomNumber');
    }

    public function randomNumber($limit)
    {
        return new JsonResponse([
            'number' => random_int(0, $limit),
        ]);
    }
}

/*
$kernel = new Kernel('dev', true);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
*/
