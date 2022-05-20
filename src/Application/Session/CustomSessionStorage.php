<?php

namespace App\Application\Session;

use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

class CustomSessionStorage extends NativeSessionStorage
{
    public function __construct(array $options = [], $handler = null, MetadataBag $metaBag = null)
    {
        parent::__construct($options, $handler, $metaBag);
    }
}
