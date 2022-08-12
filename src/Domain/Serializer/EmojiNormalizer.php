<?php

namespace App\Domain\Serializer;

use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class EmojiNormalizer implements ContextAwareNormalizerInterface, DenormalizerInterface
{
    public function normalize($object, ?string $format = null, array $context = [])
    {
        if ($context['smiley'] ?? false) {
            return (string) $object . ';)';
        }
        return (string) $object . '...';
    }

    public function supportsNormalization($data, ?string $format = null, array $context = [])
    {
        return 'emoji' === $format;
    }

    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        if ($uni = $context['unicode'] ?? false) {
            return str_replace('__', $uni, $data);
        }
        return $data . '/';
    }

    public function supportsDenormalization($data, string $type, ?string $format = null)
    {
        return 'string' === $type && 'emoji' === $format;
    }
}
