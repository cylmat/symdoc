<?php

namespace App\Tests\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppTest extends WebTestCase
{
    protected const AJAX_HEADER = ['HTTP_X-Requested-With' => 'XMLHttpRequest'];

    /**
     * @see https://automationpanda.com/2020/07/07/arrange-act-assert-a-pattern-for-writing-good-tests/
     *
     * Arrange
     * Act
     * Assert
     */
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

        # e.g. $this->assertResponseRedirects('https://example.com', 301);
    }

    # @see https://symfony.com/doc/5.0/testing/functional_tests_assertions.html

    private function access()
    {
        $client->xmlHttpRequest('POST', '/submit', ['name' => 'Fabien']);
        $client->enableProfiler();

        $session = self::$container->get('session');
        $history = $client->getHistory();
        $cookieJar = $client->getCookieJar();
    }

    private function httpAuth()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'username',
            'PHP_AUTH_PW'   => 'pa$$word',
        ]);

        $client->request('DELETE', '/post/12', [], [], [
            'PHP_AUTH_USER' => 'username',
            'PHP_AUTH_PW'   => 'pa$$word',
        ]);

        $this->assertSame('Admin Dashboard', $crawler->filter('h1')->text());

        $session = self::$container->get('session');
        $firewallName = 'secure_area';
        // if you don't define multiple connected firewalls, the context defaults to the firewall name
        // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
        $firewallContext = 'secured_area';

        // you may need to use a different token class depending on your application.
        // for example, when using Guard authentication you must instantiate PostAuthenticationGuardToken
        $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());
        $session->set('_security_' . $firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    # composer require symfony/dom-crawler
    # composer require symfony/css-selector
    private function crawl()
    {
        $response = $client->getInternalResponse();

        // the Crawler instance
        $crawler = $client->getCrawler();

        $newCrawler = $crawler->filter('input[type=submit]')
            ->last()
            ->parents()
            ->first()
        ;

        $crawler = $crawler->filterXPath('descendant-or-self::body/p');
        $crawler = $crawler->filter('body > p');

        $crawler = $crawler
            ->filter('body > p')
            ->reduce(function (Crawler $node, $i) {
                // filters every other node
                return ($i % 2) == 0;
            });

        $converter = new CssSelectorConverter();
        var_dump($converter->toXPath('div.item > h4 > a'));
    }

    private function profile()
    {
        $client = static::createClient();

        // enable the profiler only for the next request (if you make
        // new requests, you must call this method again)
        // (it does nothing if the profiler is not available)
        $client->enableProfiler();

        $crawler = $client->request('GET', '/lucky/number');

        // ... write some assertions about the Response

        // check that the profiler is enabled
        if ($profile = $client->getProfile()) {
            // check the number of requests
            $this->assertLessThan(
                10,
                $profile->getCollector('db')->getQueryCount()
            );

            // check the time spent in the framework
            $this->assertLessThan(
                500,
                $profile->getCollector('time')->getDuration()
            );
        }
    }
}
