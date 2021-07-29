<?php

declare(strict_types=1);

namespace App\Domain\Api\Dto;

use App\Domain\Entity\Domain;
use JsonSerializable;

class DomainDto implements JsonSerializable
{
    public string $id;
    public string $domain;
    public bool $isGlobal;
    public bool $isVerified;
    public string $created;

    public static function fromEntity(Domain $domain): self
    {
        $dto = new self();
        $dto->id = (string)$domain->getId();
        $dto->domain = $domain->getDomain();
        $dto->isGlobal = null === $domain->getOwner();
        $dto->isVerified = $domain->isVerified();
        $dto->created = date('Y-m-d H:i:s', (int)$domain->getUuid()->getTime());

        return $dto;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
