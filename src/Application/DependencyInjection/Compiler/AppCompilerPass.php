<?php

namespace App\Application\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AppCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        /* 
            SAMPLE
            $container->getDefinition('twig.loader.native_filesystem');
            $container->setParameter('twig.form.resources', '8');
            $container->setParameter('twig.default_path', 9);
        */
    }
}
