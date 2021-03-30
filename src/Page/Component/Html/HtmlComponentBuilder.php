<?php

declare(strict_types=1);

namespace App\Page\Component\Html;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentBuilderInterface;
use App\Page\Component\ComponentBuilderProvider;

class HtmlComponentBuilder implements ComponentBuilderInterface
{
    public function build(ComponentBuilderProvider $provider, JsonObject $json): AbstractComponent
    {
        return new HtmlComponent($json->getString('content', ''));
    }

    public function getComponentName(): string
    {
        return 'HtmlComponent';
    }
}
