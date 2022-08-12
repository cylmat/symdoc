<?php

namespace App\Domain\Serializer;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class EmojiEncoder implements EncoderInterface, DecoderInterface
{
    public function encode($data, string $format, array $context = [])
    {
        if ($context['smiley'] ?? false) {
            return '{: ' . $data . ' :}';
        }
        return '!' . $data . '!';
    }

    public function supportsEncoding(string $format)
    {
        return 'emoji' === $format;
    }

    public function decode(string $data, string $format, array $context = [])
    {
        return str_replace(['{: ',' :}', ';)'], '_', $data);
    }

    public function supportsDecoding(string $format)
    {
        return 'emoji' === $format;
    }
}
