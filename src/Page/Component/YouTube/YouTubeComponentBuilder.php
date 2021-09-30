<?php

declare(strict_types=1);

namespace App\Page\Component\YouTube;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentBuilderInterface;
use App\Page\Component\ComponentBuilderProvider;

class YouTubeComponentBuilder implements ComponentBuilderInterface
{
    public function build(ComponentBuilderProvider $provider, JsonObject $json): AbstractComponent
    {
        return new YouTubeComponent($json->getString('url', ''));
    }

    public function getComponentName(): string
    {
        return 'YouTubeComponent';
    }
}
