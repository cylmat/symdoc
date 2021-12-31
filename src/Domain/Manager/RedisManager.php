<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use App\Infrastructure\Service\RedisService;

final class RedisManager implements ManagerInterface
{
    private $redisService;

    public function __construct(RedisService $redisService)
    {
        $this->redisService = $redisService;
    }

    public function call(): array
    {
        if (!$client = $this->redisService->getClient()) {
            return [];
        }

        $client->set('foo', 'bar');
        $client->set('foo2', 'bar3');

        return [
            'foo' => $client->get('foo')
        ];
    }
}
