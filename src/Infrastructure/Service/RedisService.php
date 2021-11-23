<?php

namespace App\Infrastructure\Service;

use App\Domain\Core\Interfaces\ServiceInterface;
use Predis\Client;

class RedisService implements ServiceInterface
{
    public function getClient()
    {
        return new Client($_SERVER['REDIS_DSN']);
    }
}
