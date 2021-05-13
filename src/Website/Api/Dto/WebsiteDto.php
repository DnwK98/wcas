<?php

declare(strict_types=1);

namespace App\Website\Api\Dto;

use App\Website\Entity\Website;
use JsonSerializable;

class WebsiteDto implements JsonSerializable
{
    public string $id;
    public string $url;
    public string $created;

    public static function fromEntity(Website $website): self
    {
        $dto = new self();
        $dto->id = (string)$website->getId();
        $dto->url = $website->getUrl();
        $dto->created = date('Y-m-d H:i:s', (int)$website->getUuid()->getTime());

        return $dto;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
