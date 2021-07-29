<?php

declare(strict_types=1);

namespace App\Domain\Command;

use App\Common\CommandScheduler\Annotation\Schedule;
use App\Domain\DomainVerifier;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @Schedule("0 * * * *")
 */
class VerifyDomainsCommand extends Command
{
    protected static $defaultName = 'domain:verify';

    private DomainVerifier $domainVerifier;

    public function __construct(DomainVerifier $domainVerifier)
    {
        parent::__construct();
        $this->domainVerifier = $domainVerifier;
    }

    protected function configure()
    {
        $this->setDescription('Starts command scheduler');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->domainVerifier->verifyAllDomains();

        return 0;
    }
}
