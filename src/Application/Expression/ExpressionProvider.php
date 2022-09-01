<?php

namespace App\Application\Expression;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class ExpressionProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions()
    {
        ExpressionFunction::fromPhp('strtoupper');

        return [
            new ExpressionFunction(
                'fromprovider',
                function ($arguments, $compiledValue) {
                    return;
                },
                function ($arguments, string $str) {
                    return $str . '*prov*';
                }
            )
        ];
    }
}
