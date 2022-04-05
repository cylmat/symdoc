<?php

namespace App\Application\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

// twig.extension
class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            // LazyLoaded
            new TwigFilter('price', [AppRuntime::class, 'formatPrice']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('area', [$this, 'calculateArea']),
        ];
    }

    public function calculateArea(int $width, int $length)
    {
        return $width * $length;
    }
}
