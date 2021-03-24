<?php

namespace App\Page\Component\Html;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\ComponentBuilderInterface;
use App\Page\Component\ComponentBuilderProvider;
use App\Page\Component\ComponentInterface;

class HtmlComponentBuilder implements ComponentBuilderInterface
{
    public function build(ComponentBuilderProvider $provider, JsonObject $json): ComponentInterface
    {
        return new HtmlComponent($json->getString('content', ''));
    }

    public function getComponentName(): string
    {
        return 'HtmlComponent';
    }
}