<?php

namespace App\Application\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class TwitterClient
{
    public $configuration;

    private $transformer;
    private $logger;
    private $serializer;
    private $valueFromCP;

    public function __construct(Rot13Transformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function tweet(string $txt): string
    {
        return $this->transformer->transform($txt);
    }

    /**
     * Wither methods (called automatically with autowiring:true by Sf)
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

    public function withLogger(LoggerInterface $logger): self
    {
        $new = clone $this;
        $new->logger = $logger;

        return $new;
    }

    public function calledFromCompilerPass(string $value = '')
    {
        $this->valueFromCP = 'defined' . $value;
    }
}
