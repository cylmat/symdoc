<?php

namespace App\Domain\LocalLoader;

use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\MessageCatalogueInterface;

// must be translation.loader tagged
// read domain.locale.alias_tag (here domain.locale.arf)
class ArfTaggedLoader implements LoaderInterface
{
    public function load($resource, string $locale, string $domain = 'custom_messages'): MessageCatalogueInterface
    {
        $content = (new \SplFileObject($resource))->openFile()->fread(1000);
        $lines = \explode("\n", $content);
        $kvals = [];

        foreach ($lines as $line) {
            $kval = \explode('<=>', $line);
            $kvals[trim($kval[0])] = trim($kval[1]);
        }

        return new MessageCatalogue($locale, [
            $domain => $kvals,
        ]);
    }
}
