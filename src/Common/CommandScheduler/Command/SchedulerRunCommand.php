<?php

declare(strict_types=1);

namespace App\Common\CommandScheduler\Command;

use App\Common\CommandScheduler\CommandAnnotationsLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class SchedulerRunCommand extends Command
{
    protected static $defaultName = 'scheduler:run';

    private CommandAnnotationsLoader $annotationsLoader;

    public function __construct(CommandAnnotationsLoader $annotationsLoader)
    {
        parent::__construct();
        $this->annotationsLoader = $annotationsLoader;
    }

    protected function configure()
    {
        $this->setDescription('Starts command scheduler');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(sprintf('<info>%s</info>', 'Load scheduled jobs.'));
        $this->loadAnnotatedCommands($output);
        $output->writeln(sprintf('<info>%s</info>', 'Starting scheduler.'));
        $this->scheduler($output->isVerbose() ? $output : new NullOutput(), null);

        return 0;
    }

    private function scheduler(OutputInterface $output, $pidFile)
    {
        $input = new ArrayInput([]);

        $console = $this->getApplication();
        $command = $console->find('scheduler:execute');

        while (true) {
            $now = microtime(true);


            if (null !== $pidFile && !file_exists($pidFile)) {
                break;
            }

            $command->run($input, $output);
            usleep((int)((60 - ($now % 60) + (int)$now - $now) * 1e6));
        }
    }

    private function loadAnnotatedCommands(OutputInterface $output)
    {
        $this->annotationsLoader->load($output);
    }
}
