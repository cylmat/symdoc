<?php

namespace App\Application\DependencyInjection\Compiler;

use App\Application\Translator\CustomTranslator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Contracts\Translation\TranslatorInterface;

/*
    FrameworkExtension
$container->getDefinition('translator.data_collector')->setDecoratedService('translator');
*/

/*
data_collector.translation
    Symfony\Component\Translation\DataCollector\TranslationDataCollector
translator.data_collector
    Symfony\Component\Translation\DataCollectorTranslator
translator.default
    Symfony\Bundle\FrameworkBundle\Translation\Translator

Symfony\Contracts\Translation\TranslatorInterface
    alias for "translator.data_collector"
*/

/** *************************************
 * @todo : override default translator (DataCollectorTranslator)
 */
class TranslatorCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // Symfony\Bundle\FrameworkBundle\Translation\Translator"
        //$translatorDefinition = $container->findDefinition(CustomTranslator::class);
        //$translatorDefinition->setArgument(2, 'e');

        //$container->removeAlias('translator.data_collector');
        //$container->setAlias(CustomTranslator::class, 'translator');
    }
}
