<?php

declare(strict_types=1);

namespace App\Website\Entity;

use App\Common\Doctrine\Uuid\UuidTrait;
use App\Website\StatusEnum;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="websites_pages")
 */
class WebsitePage
{
    use UuidTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Website\Entity\Website", inversedBy="pages")
     */
    private Website $website;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $path;

    /**
     * @ORM\Column(type="text", length=32)
     */
    private string $status = StatusEnum::ACTIVE;

    /**
     * @ORM\Column(type="json")
     */
    private array $definition = [];

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $path = trim($path, '/');
        $path = trim($path, ' ');
        $this->path = $path;
    }

    public function getDefinition(): array
    {
        return $this->definition;
    }

    public function setDefinition(array $definition): void
    {
        $this->definition = $definition;
    }

    public function setWebsite(Website $website)
    {
        $this->website = $website;
    }

    public function getWebsite(): Website
    {
        return $this->website;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        if (!in_array($status, StatusEnum::list())) {
            throw new \LogicException('Invalid status');
        }

        $this->status = $status;
    }
}
