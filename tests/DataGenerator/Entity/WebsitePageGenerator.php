<?php

declare(strict_types=1);

namespace App\Tests\DataGenerator\Entity;

use App\Tests\DataGenerator\DataGenerator;
use App\Website\Entity\Website;
use App\Website\Entity\WebsitePage;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @property WebsitePage $entity
 * @method WebsitePage get()
 */
class WebsitePageGenerator extends AbstractEntityGenerator
{
    public function __construct(DataGenerator $dataGenerator, EntityManagerInterface $em)
    {
        parent::__construct($dataGenerator, $em);
        $this->entity = new WebsitePage();
        $this->withoutContent();
        $this->withPath('index');
    }

    public function forWebsite(Website $website): self
    {
        $website->addPage($this->entity);

        return $this;
    }

    public function withPath(string $path): self
    {
        $this->entity->setPath($path);

        return $this;
    }

    public function withoutContent()
    {
        $this->entity->setDefinition([]);
    }
}
