<?php

namespace App\Domain\Serializer;

use Symfony\Component\Serializer\NameConverter\AdvancedNameConverterInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class EmojiNameConverter implements AdvancedNameConverterInterface
{
    public function normalize(string $propertyName, ?string $class = null, ?string $format = null, array $context = [])
    {
        return '+' . $propertyName;
    }

    # @phpcs:disable
    public function denormalize(string $propertyName, ?string $class = null, ?string $format = null, array $context = [])
    {
        return str_replace('+', '', $propertyName);
    }
    # @phpcs:enable
}
