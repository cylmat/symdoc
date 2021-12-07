<?php

namespace App\Domain\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\RouterInterface;

class DisplayCommand extends Command
{
    protected static $defaultName = 'app:display';
    protected static $defaultDescription = 'Add a short description for your command';
    private $router;

    public function __construct(RouterInterface $router)
    {
        parent::__construct();

        $this->router = $router;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $myurl = $this->generateUrl();

        $io->success('You have a new command! < '.$myurl.' >');

        return 0;
    }

    /**
     * @see https://symfony.com/doc/4.1/console/request_context.html
     */
    private function generateUrl(): string
    {
        $context = $this->router->getContext();
        $context->setHost('example.com');
        $context->setScheme('https');
        $context->setBaseUrl('my/path');

        $url = $this->router->generate('app_application_all_index', ['param-name' => 'param-value']);

        return $url;
    }
}
