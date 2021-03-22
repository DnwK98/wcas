<?php


namespace App\Common\Doctrine\Uuid;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV6;

trait UuidTrait
{
    /**
     * @var ?string
     * @ORM\Column(name="id", type="string", length=36)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator("App\Common\Doctrine\Uuid\UuidGenerator")
     */
    protected ?string $id;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUuid(): ?UuidV6
    {
        return $this->id ? new UuidV6($this->id) : null;
    }
}