<?php

declare(strict_types=1);

namespace App\Website\Entity;

use App\Common\Doctrine\Uuid\UuidTrait;
use App\User\Entity\User;
use App\Website\Entity\Repository\WebsiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection|WebsitePage[]
     * @ORM\OneToMany(
     *     targetEntity="App\Website\Entity\WebsitePage",
     *     mappedBy="website",
     *     fetch="EXTRA_LAZY",
     *     cascade={"persist", "remove"}
     * )
     */
    private $pages;

    public function __construct()
    {
        $this->pages = new ArrayCollection();
    }

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

    /**
     * @return WebsitePage[]|Collection
     */
    public function getPages()
    {
        return $this->pages;
    }

    public function hasPage(WebsitePage $page): bool
    {
        if ($this->pages->contains($page)) {
            return true;
        }

        foreach ($this->pages as $p) {
            if ($p->getPath() === $page->getPath()) {
                return true;
            }
        }

        return false;
    }

    public function addPage(WebsitePage $page)
    {
        if (!$this->hasPage($page)) {
            $this->pages->add($page);
            $page->setWebsite($this);
        }
    }

    public function getPageById(string $id): ?WebsitePage
    {
        foreach ($this->pages as $p) {
            if ($p->getId() === $id) {
                return $p;
            }
        }

        return null;
    }
}
