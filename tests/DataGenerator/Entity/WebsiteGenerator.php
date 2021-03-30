<?php

declare(strict_types=1);

namespace App\Tests\DataGenerator\Entity;

use App\Tests\DataGenerator\DataGenerator;
use App\User\Entity\User;
use App\Website\Entity\Website;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @property Website $entity
 * @method Website get()
 */
class WebsiteGenerator extends AbstractEntityGenerator
{
    public function __construct(DataGenerator $dataGenerator, EntityManagerInterface $em)
    {
        parent::__construct($dataGenerator, $em);
        $this->entity = new Website();
        $this->withUrl('page.example.com');
    }

    public function withUrl(string $url): self
    {
        $this->entity->setUrl($url);

        return $this;
    }

    public function owner(?User $owner): self
    {
        $this->entity->setOwner($owner);

        return $this;
    }
}
