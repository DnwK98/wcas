<?php

namespace App\Tests\DataGenerator\Entity;

use App\Domain\Entity\Domain;
use App\Tests\DataGenerator\DataGenerator;
use App\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/** @property Domain $entity */
class DomainGenerator extends AbstractEntityGenerator
{

    public function __construct(DataGenerator $dataGenerator, EntityManagerInterface $em)
    {
        parent::__construct($dataGenerator, $em);
        $this->entity = new Domain();
        $this->withDomain('example.com');
    }

    public function withDomain(string $domain): self
    {
        $this->entity->setDomain($domain);
        return $this;
    }

    public function owner(?User $owner): self
    {
        $this->entity->setOwner($owner);
        return $this;
    }
}