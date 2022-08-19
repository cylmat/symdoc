<?php

declare(strict_types=1);

namespace App\Domain\LocalLoader;

use Symfony\Component\Translation\Extractor\ExtractorInterface;
use Symfony\Component\Translation\MessageCatalogue;

class ArfExtractor implements ExtractorInterface
{
    public function extract($resource, MessageCatalogue $catalogue)
    {
        d(7);
    }

    public function setPrefix(string $prefix)
    {
        d(1);
    }
}
