<?php

namespace App\Application\Service;

class TwitterClient
{
    private $transformer;

    public function __construct(Rot13Transformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function tweet(string $txt): string
    {
        return $this->transformer->transform($txt);
    }
}
