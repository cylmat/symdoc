<?php

namespace App\Application\Translator;

use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

// Should make difference between
//    Symfony\Component\Translation\Translator;
//     __construct(string $locale, MessageFormatterInterface $formatter = null,
//                 string $cacheDir = null, bool $debug = false, array $cacheVary = [])
// and
//    Symfony\Bundle\FrameworkBundle\Translation\Translator
//      __construct(ContainerInterface $container, MessageFormatterInterface $formatter,
//                  string $defaultLocale, array $loaderIds = [], array $options = [])

/**
 * @todo Doesn't work yet
 */
class CustomTranslator extends Translator implements TranslatorInterface, TranslatorBagInterface
{
    public function trans(?string $id, array $parameters = [], string $domain = null, string $locale = null)
    {
        return $this->trans($id, $parameters, $domain, $locale);
    }
}
