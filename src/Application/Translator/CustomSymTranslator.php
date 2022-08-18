<?php

namespace App\Application\Translator;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Translation\Formatter\MessageFormatterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CustomSymTranslator implements TranslatorInterface //extends Translator
{
    /*
        public function __construct(ContainerInterface $container, MessageFormatterInterface $formatter,
        string $defaultLocale, array $loaderIds = [], array $options = [])
        {
            //parent::__construct($container, $formatter, $defaultLocale, $loaderIds, $options);
        }
    */

    public function trans(?string $id, array $parameters = [], string $domain = null, string $locale = null)
    {
        return $this->trans($id, $parameters, $domain, $locale);
    }

    public function getLocale()
    {
    }

    public function setConfigCacheFactory()
    {
    }

    public function setFallbackLocales()
    {
    }

    public function setLocale()
    {
    }
}
