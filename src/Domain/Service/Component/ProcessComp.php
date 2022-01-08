<?php

namespace App\Domain\Service\Component;

use App\Domain\Service\ServiceDomainInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\InputStream;
use Symfony\Component\Process\PhpExecutableFinder;
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
        /**
         * run(), mustRun() sync
         * start() async
         */
        return [
            $this->php(),
            $this->proc(),
            $this->iterate(),
            $this->async(),
            $this->stream(),
        ];
    }

    private function php(): string
    {
        $env_vars = [
            'MESSAGE' => 'test',
            'SYMFONY_DOTENV_VARS' => false, // disable existing env var
        ];
        $process = new PhpProcess("<?= 'hi' ?>");
        $process->run(function (string $type, string $buffer) {
        }, $env_vars); // exit code

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $phpBinaryFinder = new PhpExecutableFinder();
        $phpBinaryFinder->find();

        return 'php: ' . $this->display($process);
    }

    private function proc(): string
    {
        // CREATE PROCESS
        $process = Process::fromShellCommandline('echo "${:ENV_VAR_NAME}" "${:NEW_ENV}"', null, [
            'ENV_VAR_NAME' => 'value',
        ]);
        $process->run(function (string $type, string $buffer) {
        }, [
            'NEW_ENV' => 'value2'
        ]);

        return $this->display($process);
    }

    private function iterate(): string
    {
        // CREATE PROCESS
        $process = new Process(['/bin/ls', '-lsa', /*'--option'*/], null, [], 'custom input...', 60);
        $process = new Process(['/bin/ls -lsa | grep total']); // ['...', '...', '...']
        $process->setTimeout(60);
        $process->setIdleTimeout(10); // no output produced since 10s

        // START ASYNC
        // block until all stdin sent
        $process->start(function (string $type, string $buffer) {
            // run when there is some output
            'OUT > ' . $buffer; // echo
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

    private function async(): string
    {
        $process = new Process(['whoami']); // /usr/bin/php
        $process->start(null, []);

        while ($process->isRunning()) {
            // waiting for process to finish
        }
        $process->wait(function ($type, $buffer) {
            'ERR > ' . $buffer;
        }); // BLOCKING until end

        // wait with conditions
        $process->waitUntil(function ($type, $output) {
            return $output === 'Ready. Waiting for commands...';
        });

        /*********************
         * Need PCNTL extension
         */
        // can be stopped
        // $process->stop(3, SIGINT);
        // $process->signal(SIGKILL);

        // $process->disableOutput();
        $echo = $process->getOutput();

        return $this->display($process);
    }

    private function stream(): string
    {
        $process = new Process(['cat']); // input via 4th arg
        $process->setInput('input from setter');
        // $process->setTty(Process::isTtySupported()); can't get output anymore
        $process->run();

          //or

        $input = new InputStream();
        $input->write('foo from inputstream: then add -');

        $process = new Process(['cat']); // input via 4th arg
        $process->setInput($input);
        $process->start(); //async
        $input->write('bar- from write()');
        $input->close(); // need to be closed explicitely!
        $process->wait(); // mandatory!

          // or

        /*$stream = fopen('php://temporary', 'w+');

        $process = new Process(['cat']);
        $process->setInput($stream);
        $process->start();
        fwrite($stream, 'foo from php stream');
        fwrite($stream, 'bar inside!');
        fclose($stream);
        $process->wait();*/

        return $this->display($process);
    }

    private function display(Process $process): string
    {
        return $process->getPid() . ': ' .
            trim($process->getOutput()) .
            " (" . $process->getExitCode() .
            ")" . trim($process->getErrorOutput());
    }
}
