<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use App\Infrastructure\Service\RedisService;

class RedisManager implements ManagerInterface
{
    private $redisService;

    public function __construct(RedisService $redisService)
    {
        $this->redisService = $redisService;
    }

    public function call()
    {
        $client = $this->redisService->getClient();
        $client->set('foo', 'bar');
        $client->set('foo2', 'bar3');
        $value = $client->get('foo');
    }
}
