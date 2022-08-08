<?php

namespace App\Application\Command;

use App\Application\Service\DateTimeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

// tag console.command
// http://docopt.org/
// Symfony\Component\Console\CommandLoader\FactoryCommandLoader
class NewCommand extends Command
{
    use LockableTrait; // use lock

    // InputArgument
    // const REQUIRED = 1;
    // const OPTIONAL = 2;
    // const IS_ARRAY = 4;

    //Command Lifecycle
    // initialize
    // interact
    // execute

    protected static $defaultName = 'command:new';
    //ex: command:new pass alpha beta gamma -o oui -o no -v

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

        // arguments ordered
        $this->addArgument('password', InputArgument::OPTIONAL, 'User password', 'default');
        $this->addArgument('other', InputArgument::IS_ARRAY, 'Other thing', ['all is ok']);

        // options with - or --
        $this->addOption(
            'optioning',
            'o',
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Option for it',
            ['def-option']
        );

        // private command
        $this->setHidden(false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // run other command
        $return = $this->getApplication()->find('about')->run(new ArrayInput([
            //'name' => 'arg',
            //'--opt' => null,
        ]), new NullOutput());

        //////////////////////////////////
        // execute
        $section1 = $output->section();
        $io = new SymfonyStyle($input, $section1);
        $io->title('User custom at ' . $this->dateTimeService->getDateTime()->format("H:m"));

        // io
        // title | section | text | listing | table | horizontalTable ...

        $output->getFormatter()->setStyle(
            'foo',
            new OutputFormatterStyle('red', 'yellow', ['bold', 'blink'])
        );

        $io->progressStart(100);
        foreach (range(0, 99) as $r) {
            usleep(10000);
            $io->progressAdvance(1);
        }
        $io->progressFinish();

        $section1->writeln("<foo>Arguments :</foo>");
        foreach ($a = $input->getArguments() as $key => &$arg) {
            $a[$key] = "$key : " . (\is_array($arg) ? join(',', $arg) : $arg);
        }
        $section1->writeln($a);
        $section1->writeln("<href=https://symfony.com><fg=black;bg=yellow;options=bold>test</>");

        $section1->write(
            "<info>O</info><question>p</question><comment>t</comment>ionin<error>g</error> :"
        );
        $section1->writeln(
            $input->getOption("optioning"),
            OutputInterface::VERBOSITY_NORMAL
        );

        // available methods: ->isQuiet(), ->isVerbose(), ->isVeryVerbose(), ->isDebug()
        if ($output->isVerbose()) {
            $output->writeln('User class: ' . __CLASS__);
        }

        $io->success('Yes');

        //////////////////////////
        // helpers
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Please select your favorite color (defaults to red)',
            ['red', 'blue', 'yellow'],
            0
        );
        $question->setErrorMessage('Color %s is invalid.');

        //$color = $helper->ask($input, $output, $question);

        $formatter = $this->getHelper('formatter');
        $formattedLine = $formatter->formatSection(
            'SomeSection',
            'Here is some message related to that section'
        );
        $output->writeln($formattedLine);

        $table = new Table($output);
        $table
            ->setHeaders(['ID', 'Title', 'Author'])
            ->setRows([
                ['58-10-7',  'Testing', 'Framework'],
                new TableSeparator(),
                ['5-0210-0', 'Alpha', 'OMG'],
            ])
        ;
        $table->setStyle('borderless')->render();

        $debugFormatter = $this->getHelper('debug_formatter');
        $process = new Process(['ls']);
        $output->writeln($debugFormatter->start(
            spl_object_hash($process),
            'Some process description',
            'STARTED'
        ));

        $output->writeln(
            $debugFormatter->stop(
                spl_object_hash($process),
                'Some process description',
                'STOPPED'
            )
        );
        $process->run();

        /////////////////////////
        // lock

        //if (!$this->lock()) {
        //$output->writeln('The command is already running in another process.');

        return 0; // all ok
        //}
        //$this->release();
    }
}
