<?php

namespace App\Tests\Application\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;

class AppTest extends WebTestCase
{
    /** @var KernelBrowser $client */
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
        $routes = self::$client->getContainer()->get('router')->getRouteCollection()->all();

        foreach ($routes as $name => $route) {
            /** @var Route $route */
            if (false === strpos($name, 'application')) {
                continue;
            }

            // avoid http-request tests
            if (
                key_exists('GITHUB_ACTIONS', $_SERVER)
                && \in_array($name, [
                    'app_application_basics_cache',
                    'app_application_utilities_httpclient',
                ])
            ) {
                continue;
            }
            $crawler = self::$client->request('GET', $route->getPath());

            echo PHP_EOL;
            try {
                echo ("Testing " . $route->getPath() . "...") . PHP_EOL;
                $this->assertEquals(
                    Response::HTTP_OK,
                    self::$client->getResponse()->getStatusCode(),
                    'Route "' . $route->getPath() . '" is not returning code -200-!'
                );
            } catch (\Exception $e) {
                var_dump($crawler->filter('h1')->last()->text());
                throw $e;
            }
        }
    }
}
