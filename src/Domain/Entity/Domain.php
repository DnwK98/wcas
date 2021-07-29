<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Common\Doctrine\Uuid\UuidTrait;
use App\Domain\Entity\Repository\DomainRepository;
use App\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DomainRepository::class)
 * @ORM\Table(name="domains")
 */
class Domain
{
    use UuidTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\User\Entity\User", cascade={"persist"}, fetch="EAGER")
     */
    private ?User $owner;

    /**
     * @ORM\Column(type="text", length=128)
     */
    private string $domain;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $verified = false;

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): void
    {
        $this->owner = $owner;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): void
    {
        $this->verified = $verified;
    }
}
