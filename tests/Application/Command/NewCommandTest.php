<?php

namespace App\Tests\Application\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateUserCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('command:new');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
            // pass arguments to the helper
            'password' => 'Wouter',
            '-o' => 'opt',

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
            ],
            // use "section" of console output
            ['capture_stderr_separately' => true]
        );

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('User custom', $output);
    }
}
