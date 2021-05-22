<?php

declare(strict_types=1);

namespace App\Common\Doctrine\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WaitForMySqlCommand extends Command
{
    protected static $defaultName = 'mysql:wait';

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        $this->setDescription('Starts command scheduler');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $timeout = 120;
        $lastError = null;

        for ($i = 1; $i <= $timeout; ++$i) {
            try {
                /** @psalm-suppress UndefinedInterfaceMethod // Invalid interface returned from executeQuery */
                $this->em->getConnection()->executeQuery('SELECT VERSION()')->fetchAllAssociative();

                return 0;
            } catch (\Throwable $e) {
                $lastError = $e;
                sleep(1);
                $output->writeln("Wait for mysql $i / $timeout ...");
            }
        }

        $output->writeln('[error] Timeout exceeded during waiting for MySql');
        $output->writeln($lastError->getMessage());

        return 0;
    }
}
