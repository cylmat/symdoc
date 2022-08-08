<?php

namespace App\Application\Command;

use App\Application\Service\DateTimeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// tag console.command
class NewCommand extends Command
{
    // InputArgument
    // const REQUIRED = 1;
    // const OPTIONAL = 2;
    // const IS_ARRAY = 4;

    //Command Lifecycle
    // initialize
    // interact
    // execute

    protected static $defaultName = 'command:new';

    private DateTimeService $dateTimeService;

    public function __construct(DateTimeService $dateTimeService)
    {
        parent::__construct();

        $this->dateTimeService = $dateTimeService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Custom new command')
            ->setHelp('This is a new one')
        ;

        $this->addArgument('password', InputArgument::OPTIONAL, 'User password', 'default');
        $this->addArgument('other', InputArgument::OPTIONAL, 'Other thing', 'all is ok');

        $this->addOption('optioning', 'o', InputArgument::IS_ARRAY, 'Option for it', 'def-option');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $section1 = $output->section();

        $section1->writeln([
            'User custom at ' . $this->dateTimeService->getDateTime()->format("H:m"),
            "==========="
        ]);

        $section1->writeln("Arguments:");
        foreach ($a = $input->getArguments() as $key => &$arg) {
            $a[$key] = "$key : $arg";
        }
        $section1->writeln($a);
        $section1->writeln("");

        $section1->write("Optioning:");
        $section1->writeln($input->getOption("optioning"));

        return 0; // all ok
    }
}
