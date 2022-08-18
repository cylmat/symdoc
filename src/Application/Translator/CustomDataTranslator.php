<?php

namespace App\Application\Translator;

use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CustomDataTranslator implements TranslatorInterface, TranslatorBagInterface, LocaleAwareInterface
{
    private DataCollectorTranslator $dataCollector;

    public function __construct(TranslatorInterface $translator)
    {
        $this->dataCollector = new DataCollectorTranslator($translator);
    }

    public function getCatalogue(?string $locale = null)
    {
        return $this->dataCollector->getCatalogue($locale);
    }

    public function setLocale(string $locale)
    {
        return $this->dataCollector-> setLocale($locale);
    }

    public function getLocale()
    {
        return $this->dataCollector->getLocale();
    }

    public function trans(?string $id, array $parameters = [], string $domain = null, string $locale = null)
    {
        return $this->dataCollector->trans($id = (string) $id, $parameters, $domain, $locale);
    }

    public function __call(string $method, array $args)
    {
        return $this->dataCollector->{$method}(...$args);
    }
}
