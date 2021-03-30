<?php

declare(strict_types=1);

namespace App\Tests\DataGenerator;

use App\Tests\DataGenerator\Entity\DomainGenerator;
use App\Tests\DataGenerator\Entity\WebsitePageGenerator;
use App\Tests\DataGenerator\Entity\UserGenerator;
use App\Tests\DataGenerator\Entity\WebsiteGenerator;
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

    public function website(): WebsiteGenerator
    {
        return new WebsiteGenerator($this, $this->em);
    }

    public function websitePage(): WebsitePageGenerator
    {
        return new WebsitePageGenerator($this, $this->em);
    }
}
