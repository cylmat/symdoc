<?php

namespace App\Application\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppTest extends WebTestCase
{
    private static $client;

    public static function setUpBeforeClass()
    {
        self::$client = static::createClient();
    }

    public function testAllRoutes(): void
    {       
        self::$client->request('GET', '/all');
        $this->assertResponseIsSuccessful();

        self::$client->request('GET', '/doctrine');
        $this->assertResponseIsSuccessful();

        self::$client->request('GET', '/misc');
        $this->assertResponseIsSuccessful();
    }
}
