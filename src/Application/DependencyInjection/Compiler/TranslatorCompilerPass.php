<?php

namespace App\Application\DependencyInjection\Compiler;

use App\Application\Translator\CustomDataTranslator;
use App\Application\Translator\CustomSymTranslator;
use App\Application\Translator\CustomTranslator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Contracts\Translation\TranslatorInterface;

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

// Should make difference between
//    Symfony\Component\Translation\Translator;
//     __construct(string $locale, MessageFormatterInterface $formatter = null,
//                 string $cacheDir = null, bool $debug = false, array $cacheVary = [])
// and
//    Symfony\Bundle\FrameworkBundle\Translation\Translator
//      __construct(ContainerInterface $container, MessageFormatterInterface $formatter,

/*
 * Loop !
 * $f = $container->getDefinition('translator');
 * $container->setDefinition('Symfony\Contracts\Translation\TranslatorInterface', $n);
 * $container->setAlias('translator', 'Symfony\Contracts\Translation\TranslatorInterface');
 */

/**
 * Must be after TranslatorPass
 *    $containerBuilder->addCompilerPass(new TranslatorCompilerPass(), PassConfig::TYPE_AFTER_REMOVING, -10);
 */
class TranslatorCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // not needed: $container->getDefinition('custom.translator')->setDecoratedService('translator');

        // override Symfony\Contracts\Translation\TranslatorInterface alias for "translator.data_collector"
        $container->setAlias('Symfony\Contracts\Translation\TranslatorInterface', 'custom.translator');
    }
}
