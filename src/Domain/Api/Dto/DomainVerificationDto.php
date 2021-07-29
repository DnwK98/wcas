<?php

declare(strict_types=1);


namespace App\Domain\Api\Dto;


use App\Domain\Entity\Domain;
use JsonSerializable;

class DomainVerificationDto implements JsonSerializable
{
    private string $id;
    private string $domain;
    private bool $isVerified;
    private array $ipAddresses;

    public static function Create(Domain $domain, array $ipAddresses): self
    {
        $obj = new self();
        $obj->id = $domain->getId();
        $obj->domain = $domain->getDomain();
        $obj->isVerified = $domain->isVerified();
        $obj->ipAddresses = $ipAddresses;

        return $obj;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
