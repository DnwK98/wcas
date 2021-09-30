<?php

declare(strict_types=1);

namespace App\Serve;

use App\Common\JsonObject\JsonObject;
use App\Common\Url\Url;
use App\Page\PageBuilder;
use App\Serve\Cache\CacheInterface;
use App\Website\WebsiteService;

class ServeService
{
    private CacheInterface $cache;
    private WebsiteService $websiteService;
    private PageBuilder $pageBuilder;

    public function __construct(CacheInterface $cache, WebsiteService $websiteService, PageBuilder $pageBuilder)
    {
        $this->cache = $cache;
        $this->websiteService = $websiteService;
        $this->pageBuilder = $pageBuilder;
    }

    public function getUrlContent(string $url): ?string
    {
        $url = rtrim($url, ' /');
        $definition = $this->cache->getOrExecute($url, function () use ($url) {
            $page = $this->websiteService->getPageForUrl(Url::fromString($url));

            return $page ? json_encode($page->definition) : null;
        });

        if (null === $definition) {
            return null;
        }

        return $this->pageBuilder
            ->build(JsonObject::ofJson($definition))
            ->render();
    }
}
