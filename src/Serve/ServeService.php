<?php

declare(strict_types=1);

namespace App\Serve;

use App\Common\JsonObject\JsonObject;
use App\Common\Url\Url;
use App\Page\PageFactory;
use App\Serve\Cache\CacheInterface;
use App\Website\WebsiteService;

class ServeService
{
    private CacheInterface $cache;
    private WebsiteService $websiteService;
    private PageFactory $pageFactory;

    public function __construct(CacheInterface $cache, WebsiteService $websiteService, PageFactory $pageFactory)
    {
        $this->cache = $cache;
        $this->websiteService = $websiteService;
        $this->pageFactory = $pageFactory;
    }

    public function getUrlContent(string $url): ?string
    {
        $url = rtrim($url, ' /');

        return $this->cache->getOrExecute($url, function () use ($url) {
            $page = $this->websiteService->getActivePageForUrl(Url::fromString($url));

            $definition = $page ? json_encode($page->definition) : null;
            if (null === $definition) {
                return null;
            }

            return $this->pageFactory
                ->build(JsonObject::ofJson($definition))
                ->render();
        });
    }
}
