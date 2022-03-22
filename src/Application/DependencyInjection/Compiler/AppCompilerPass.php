<?php

namespace App\Application\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

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

        $definition = new Definition('twig.form.resources', [
            'constructor',
            'arguments',
            new Reference('doctrine'), // reference to an existing service
        ]);
        $args = $definition->getArguments();

        $container->setDefinition('pass.twig.form.resources', $definition);
        // or shortcut
        $container->register('pass.twig.form.resources', 'twig.form.resources');

        // TAGGES
        $testTaggedServices = $container->findTaggedServiceIds('app.test');
        foreach ($testTaggedServices as $id => $tags) {
            //twitter_client
            $definition = $container->findDefinition($id) // like getDefinition but with alias
                ->setPrivate(true)
                ->setAutowired(true);

            $definition->addMethodCall('calledFromCompilerPass', ['custom9']);
        }
    }
}
