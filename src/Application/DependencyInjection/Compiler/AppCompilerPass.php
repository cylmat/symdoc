<?php

namespace App\Application\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Manipulate other service definitions
 */
class AppCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $twigDefinition = $container->getDefinition('twig.loader.native_filesystem');
            // ->set Property, Factory, Class, Argument, MethodCall, Autoconfigured, Tags, Shared...
        $twigDefinition->setPublic(false);

        $container->setParameter('twig.form.resources', '8');
        $container->setParameter('twig.default_path', 9);

        // TAGGES
        $testTaggedServices = $container->findTaggedServiceIds('app.test');
        foreach ($testTaggedServices as $id => $tags) {
            //twitter_client
            $definition = $container->getDefinition($id)
                ->setPrivate(true)
                ->setAutowired(true);

            $definition->addMethodCall('calledFromCompilerPass', ['custom9']);
        }
    }
}
