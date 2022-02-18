<?php

namespace App\Application\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class TwitterClientChild extends TwitterClient
{
    private $transformer;
    private $logger;
    private $serializer;

    public function __construct(Rot13Transformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function tweet(string $txt): string
    {
        return $this->transformer->transform($txt);
    }

    /**
     * Wither methods (called automatically on autowiring by Sf)
     * @see https://symfony.com/blog/new-in-symfony-4-3-configuring-services-with-immutable-setters
     *
     * @required
     * @return static
     */
    public function withMyAutowiredLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    public function withConfiguredSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;

        return $this;
    }
}
