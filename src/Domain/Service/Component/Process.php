<?php

namespace App\Domain\Service\Component;

use App\Domain\Service\ServiceDomainInterface;
use Symfony\Component\Process\PhpProcess;

class Process implements ServiceDomainInterface
{
    public function use(): array
    {
        $message = 'Hello world';
        $process = new PhpProcess("<?php echo '$message';");
        $process->run();

        return [$process->getOutput()];
    }
}
