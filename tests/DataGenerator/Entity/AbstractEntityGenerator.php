<?php


namespace App\Tests\DataGenerator\Entity;


use App\Tests\DataGenerator\DataGenerator;
use Doctrine\ORM\EntityManagerInterface;

class AbstractEntityGenerator
{
    protected object $entity;
    protected EntityManagerInterface $em;
    protected DataGenerator $dataGenerator;

    public function __construct(DataGenerator $dataGenerator, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->dataGenerator = $dataGenerator;
    }

    public function persistent()
    {
        $this->em->persist($this->entity);
    }
}