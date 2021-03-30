<?php

declare(strict_types=1);

namespace App\Website\Api\Dto;

use App\Website\Entity\WebsitePage;
use JsonSerializable;

class WebsitePageDto implements JsonSerializable
{
    public string $id;
    public string $path;
    public string $definition;
    public string $created;

    public static function fromEntity(WebsitePage $page): self
    {
        $dto = new self();
        $dto->id = (string)$page->getId();
        $dto->path = $page->getPath();
        $dto->definition = json_encode($page->getDefinition());
        $dto->created = date('Y-m-d H:i:s', (int)$page->getUuid()->getTime());

        return $dto;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
