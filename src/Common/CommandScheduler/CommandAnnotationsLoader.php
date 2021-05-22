<?php

declare(strict_types=1);

namespace App\Common\CommandScheduler;

use App\Common\CommandScheduler\Annotation\Schedule;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use JMose\CommandSchedulerBundle\Entity\Repository\ScheduledCommandRepository;
use JMose\CommandSchedulerBundle\Entity\ScheduledCommand;
use Laminas\Code\Reflection\ClassReflection;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandAnnotationsLoader
{
    private CommandLoaderInterface $commandLoader;
    private AnnotationReader $annotationsReader;
    private ?OutputInterface $output;
    private EntityManagerInterface $em;
    private ScheduledCommandRepository $scheduledCommandRepository;

    public function __construct(ContainerInterface $container, EntityManagerInterface $em)
    {
        $this->commandLoader = $container->get('console.command_loader');
        $this->annotationsReader = new AnnotationReader();
        $this->em = $em;

        /** @var ScheduledCommandRepository $scheduledCommandRepository */
        $scheduledCommandRepository = $this->em->getRepository(ScheduledCommand::class);
        $this->scheduledCommandRepository = $scheduledCommandRepository;
    }

    public function load(?OutputInterface $output = null)
    {
        $this->output = $output;

        $this->em->transactional(function () {
            $this->clearCommands();
            foreach ($this->getCommands() as $command) {
                $this->processCommand($command);
            }
        });

        $this->output = null;
    }

    /**
     * @return Command[]
     */
    private function getCommands(): array
    {
        $commands = [];
        foreach ($this->commandLoader->getNames() as $commandName) {
            $commands[] = $this->commandLoader->get($commandName);
        }

        return $commands;
    }

    private function processCommand(Command $command)
    {
        try {
            $reflection = new ClassReflection(get_class($command));
            $annotations = array_filter($this->annotationsReader->getClassAnnotations($reflection), function ($a) {
                return $a instanceof Schedule;
            });
            foreach ($annotations as $annotation) {
                $this->addSchedule($command, $annotation);
            }
        } catch (\Throwable $e) {
            $this->log("[Error] {$e->getMessage()}");
        }
    }

    private function log($m)
    {
        if ($this->output) {
            $this->output->writeln($m);
        }
    }

    private function clearCommands()
    {
        $this->scheduledCommandRepository->createQueryBuilder('c')
            ->delete()
            ->getQuery()
            ->execute();
    }

    private function addSchedule(Command $command, Schedule $annotation)
    {
        $commandName = $command::getDefaultName() ? $command::getDefaultName() : $annotation->command;

        $scheduledCommand = new ScheduledCommand();
        $scheduledCommand->setName($annotation->name);
        $scheduledCommand->setCommand($commandName);
        $scheduledCommand->setArguments($annotation->arguments);
        $scheduledCommand->setCronExpression($annotation->cron);
        $scheduledCommand->setPriority(1);
        $scheduledCommand->setExecuteImmediately(0);
        $scheduledCommand->setLocked(false);
        $scheduledCommand->setDisabled(false);

        $this->em->persist($scheduledCommand);
        $this->em->flush();

        $this->log("Add command schedule ($annotation->cron) $commandName $annotation->arguments");
    }
}
