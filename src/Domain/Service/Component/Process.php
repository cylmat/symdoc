<?php

namespace App\Domain\Service\Component;

use App\Domain\Service\ServiceDomainInterface;
use Symfony\Component\Process\PhpProcess;

class Process implements ServiceDomainInterface
{
    public function use(): array
    {
        return [
            $this->runProcess()
        ];
    }

    private function runProcess(): string
    {
        $process = new PhpProcess("ls -lsa");
        $process->run();

        return $process->getOutput();
    }
}
