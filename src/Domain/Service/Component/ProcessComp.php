<?php

namespace App\Domain\Service\Component;

use App\Domain\Service\ServiceDomainInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\PhpProcess;
use Symfony\Component\Process\Process;

class ProcessComp implements ServiceDomainInterface
{
    /**
     * Replace 
     * exec - Execute an external program
     *     exec(string $command, array &$output = null, int &$result_code = null): string|false
     * passthru — Execute an external program and display raw output
     *     passthru(string $command, int &$result_code = null): ?bool
     * shell_exec — Execute command via shell and return the complete output as a string
     *     shell_exec(string $command): string|false|null
     * system — Execute an external program and display the output
     *     system(string $command, int &$result_code = null): string|false
     */
    /**
     * @see proc_open — Execute a command and open file pointers for input/output 
     * https://www.php.net/manual/en/function.proc-open.php
     */
    public function use(): array
    {
        return [
            $this->php(),
            $this->iterate(),
        ];
    }

    private function php(): string
    {
        $process = new PhpProcess("<?= 'hi' ?>");
        $process->run(); // exit code

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return 'php: ' . $this->display($process);
    }

    private function iterate(): string
    {
        // CREATE PROCESS
        $process = Process::fromShellCommandline('...', null, ['ENV_VAR_NAME' => 'value']);
        $process = new Process(['/bin/ls', '-lsa', /*'--option'*/]); 
        $process = new Process(['/bin/ls -lsa | grep total']); // ['...', '...', '...']

        // START
        $process->start(function(string $env) { // block until all stdin sent
            // run when there is some output
        });

        $output = '';
        
        // GET RESULTS
        $iterator = $process->getIterator($process::ITER_SKIP_OUT);
        foreach (/*process*/ $iterator as $type => $data) {
            if ($process::OUT === $type) {
                //echo "\nRead from stdout: ".$data;
            } elseif ($process::ERR === $type) {
                $output .= trim($data);
            }
        }

        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
            $output .= ' -failed from mustRun()-';
        }

        return $output;
    }

    private function display(Process $process): string
    {
        return $process->getOutput() . "(" . $process->getExitCode() . ")" . $process->getErrorOutput();
    }
}
