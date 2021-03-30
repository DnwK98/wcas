<?php

declare(strict_types=1);

namespace App\Website\Entity;

use App\Common\Doctrine\Uuid\UuidTrait;
use App\User\Entity\User;
use App\Website\Entity\Repository\WebsiteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WebsiteRepository::class)
 * @ORM\Table(name="websites")
 */
class Website
{
    use UuidTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\User\Entity\User", cascade={"persist"}, fetch="EAGER")
     */
    private ?User $owner;

    /**
     * @ORM\Column(type="text", length=128)
     */
    private string $url;

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): void
    {
        $this->owner = $owner;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }
}
