<?php

namespace App\Application\DependencyInjection\Compiler;

use App\Application\Translator\CustomTranslator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Contracts\Translation\TranslatorInterface;

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

        $container->setParameter('twig.form.resources.sample', '8');
        $container->setParameter('twig.default_path.sample', 9);

        // long way...
        $definition = new Definition('twig.form.resources.sample', [
            'constructor',
            'arguments',
            new Reference('doctrine'), // reference to an existing service
        ]);
        $args = $definition->getArguments();
        $container->setDefinition('pass.twig.form.resources.sample', $definition);

        // ...or shortcut
        $container->register('pass.twig.form.resources.sample', 'twig.form.resources.sample');

        // TAGS
        $testTaggedServices = $container->findTaggedServiceIds('app.test');
        foreach ($testTaggedServices as $id => $tags) {
            //twitter_client
            // Symfony\Component\DependencyInjection\Definition
            $definition = $container->findDefinition($id) // like getDefinition but with alias
                ->setPrivate(true)
                ->setAutowired(true);

            // a service could have the same tag twice
            // Use aliased tags
            foreach ($tags as $attributes) {
            //      $attributes['alias'];
            }

            $definition->addMethodCall('calledFromCompilerPass', ['custom9']);
        }
    }
}
