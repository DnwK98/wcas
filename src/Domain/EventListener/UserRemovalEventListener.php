<?php

declare(strict_types=1);


namespace App\Domain\EventListener;


use App\Domain\Entity\Repository\DomainRepository;
use App\User\Event\UserRemovalEvent;

class UserRemovalEventListener
{
    private DomainRepository $repository;

    public function __construct(DomainRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UserRemovalEvent $event)
    {
        $this->repository->removeUserDomains($event->getUser()->getId());
    }
}
