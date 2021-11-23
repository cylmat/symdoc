<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use Predis\Client;

class RedisManager implements ManagerInterface
{
    public function call()
    {
        $client = new Client();
        $client->set('foo', 'bar');
        $value = $client->get('foo');
        d($value);
    }
}
