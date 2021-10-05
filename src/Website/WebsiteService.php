<?php

declare(strict_types=1);

namespace App\Website;

use App\Common\Url\Url;
use App\User\Entity\User;
use App\Website\Api\Dto\WebsitePageDetailsDto;
use App\Website\Entity\Repository\WebsiteRepository;

class WebsiteService
{
    private WebsiteRepository $websiteRepository;

    public function __construct(WebsiteRepository $websiteRepository)
    {
        $this->websiteRepository = $websiteRepository;
    }

    public function getActivePageForUrl(Url $url): ?WebsitePageDetailsDto
    {
        $website = $this->websiteRepository->findOneBy(['url' => $url->getDomain()]);

        if (null === $website) {
            return null;
        }

        $path = ltrim($url->getPath(), ' /');
        $page = $website->getPageByPath($path);

        if (null === $page) {
            return null;
        }

        if (StatusEnum::INACTIVE === $page->getStatus() || StatusEnum::INACTIVE === $website->getStatus()) {
            return null;
        }

        return WebsitePageDetailsDto::fromEntity($page);
    }

    public function removeUserWebsites(User $user)
    {
        $this->websiteRepository->removeUserWebsites($user->getId());
    }
}
