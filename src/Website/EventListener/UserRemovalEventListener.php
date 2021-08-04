<?php

declare(strict_types=1);


namespace App\Website\EventListener;


use App\User\Event\UserRemovalEvent;
use App\Website\WebsiteService;

class UserRemovalEventListener
{
    private WebsiteService $websiteService;

    public function __construct(WebsiteService $websiteService)
    {
        $this->websiteService = $websiteService;
    }

    public function __invoke(UserRemovalEvent $event)
    {
        $this->websiteService->removeUserWebsites($event->getUser());
    }
}
