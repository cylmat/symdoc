<?php

namespace App\Domain\Message;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class CustomSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        if (empty($encodedEnvelope['body'])) {
            throw new MessageDecodingFailedException('Encoded envelope should have at least a body.');
        }
        $serializeEnvelope = stripslashes($encodedEnvelope['body']);

        return unserialize($serializeEnvelope) . 'done';
    }

    public function encode(Envelope $envelope): array
    {
        $envelope = $envelope->withoutStampsOfType(NonSendableStampInterface::class);
        $body = addslashes(serialize($envelope));

        return [
            'body' => $body
        ];
    }
}
