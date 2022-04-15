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
    }
}
