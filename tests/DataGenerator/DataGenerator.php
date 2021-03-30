<?php


namespace App\Tests\DataGenerator;


use App\Tests\DataGenerator\Entity\DomainGenerator;
use App\Tests\DataGenerator\Entity\UserGenerator;
use Doctrine\ORM\EntityManagerInterface;

class DataGenerator
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function user(): UserGenerator
    {
        return new UserGenerator($this, $this->em);
    }

    public function domain(): DomainGenerator
    {
        return new DomainGenerator($this, $this->em);
    }
}