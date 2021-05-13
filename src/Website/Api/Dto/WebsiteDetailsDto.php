<?php

declare(strict_types=1);

namespace App\Website\Api\Dto;

use App\Website\Entity\Website;
use App\Website\Entity\WebsitePage;
use JsonSerializable;

class WebsiteDetailsDto implements JsonSerializable
{
    public string $id;
    public string $url;
    public string $created;

    /** @var WebsitePageDto[] */
    public array $pages;

    public static function fromEntity(Website $website): self
    {
        $dto = new self();
        $dto->id = (string)$website->getId();
        $dto->url = $website->getUrl();
        $dto->created = date('Y-m-d H:i:s', (int)$website->getUuid()->getTime());
        $dto->pages = $website->getPages()
            ->map(function (WebsitePage $page) {
                return WebsitePageDto::fromEntity($page);
            })
            ->toArray();

        return $dto;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
