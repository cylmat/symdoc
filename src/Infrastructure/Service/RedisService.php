<?php

namespace App\Infrastructure\Service;

use App\Domain\Core\Interfaces\ServiceInterface;
use Predis\Client;

class RedisService implements ServiceInterface
{
    private $redis_dsn;

    public function __construct(string $redis_dsn)
    {
        $this->redis_dsn = $redis_dsn;
    }

    public function getClient(): ?Client
    {
        // in case dsn is empty (during test cases) return null
        return $this->redis_dsn ? new Client($this->redis_dsn) : null;
    }
}
