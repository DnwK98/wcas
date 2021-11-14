<?php

declare(strict_types=1);

namespace App\Page\Component\YouTube;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentFactoryInterface;
use App\Page\Component\ComponentFactoryProvider;

class YouTubeComponentFactory implements ComponentFactoryInterface
{
    public function build(ComponentFactoryProvider $provider, JsonObject $json): AbstractComponent
    {
        return new YouTubeComponent($json->getString('url', ''));
    }

    public function getComponentName(): string
    {
        return 'YouTubeComponent';
    }
}
