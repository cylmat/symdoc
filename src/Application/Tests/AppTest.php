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

    /**
     * @see https://automationpanda.com/2020/07/07/arrange-act-assert-a-pattern-for-writing-good-tests/
     * 
     * Arrange
     * Act
     * Assert
     */
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
