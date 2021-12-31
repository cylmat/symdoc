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
        if (key_exists('GITHUB_ACTIONS', $_SERVER)) {
            $this->assertTrue(true); // skip "risky" test on github
            return;
        }

        $routes = self::$client->getContainer()->get('router')->getRouteCollection()->all();

        foreach ($routes as $name => $route) {
            /** @var Route $route */
            if (false === strpos($name, 'application')) {
                continue;
            }

            $crawler = self::$client->request('GET', $route->getPath());

            echo PHP_EOL;
            try {
                echo 'Functional testing on : ';
                $this->assertEquals(
                    Response::HTTP_OK,
                    self::$client->getResponse()->getStatusCode(),
                    'Route "' . $route->getPath() . '" is not returning code -200-!'
                );
                echo ($route->getPath() . " \t ...[OK]");
            } catch (\Exception $e) {
                var_dump($crawler->filter('h1')->last()->text());
                throw $e;
            }
        }
    }

    public function testAllManagers()
    {
        self::bootKernel();
        echo PHP_EOL;
        foreach (self::$container->getServiceIds() as $serviceId) {
            if (!preg_match('/Manager$/', $serviceId)) {
                continue;
            }

            /** ManagerInterface $manager */
            $manager = self::$container->get($serviceId);
            $result = $manager->call();

            echo "Manager test on : $serviceId ";
            $this->assertTrue(\is_array($result)); // assert no error
            echo "\t ...[OK] " . PHP_EOL;
        }
    }
}
