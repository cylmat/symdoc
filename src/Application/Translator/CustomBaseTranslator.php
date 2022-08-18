<?php

namespace App\Application\Translator;

use Symfony\Component\Translation\Formatter\IntlFormatterInterface;
use Symfony\Component\Translation\Formatter\MessageFormatter;
use Symfony\Component\Translation\Formatter\MessageFormatterInterface;
use Symfony\Component\Translation\Translator;

// Can't be autowired without config!
class CustomBaseTranslator //extends Translator
{
    public function __construct(
        string $locale,
        MessageFormatterInterface $formatter = null,
        string $cacheDir = null,
        bool $debug = false,
        array $cacheVary = []
    ) {
        $this->setLocale($locale);

        if (null === $formatter) {
            $formatter = new MessageFormatter();
        }

        $this->formatter = $formatter;
        $this->cacheDir = $cacheDir;
        $this->debug = $debug;
        $this->cacheVary = $cacheVary;
        $this->hasIntlFormatter = $formatter instanceof IntlFormatterInterface;
    }

    public function trans(?string $id, array $parameters = [], string $domain = null, string $locale = null)
    {
        return $this->trans($id, $parameters, $domain, $locale);
    }
}
