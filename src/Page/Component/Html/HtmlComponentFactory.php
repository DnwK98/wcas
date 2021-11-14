<?php

declare(strict_types=1);

namespace App\Page\Component\Html;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentFactoryInterface;
use App\Page\Component\ComponentFactoryProvider;

class HtmlComponentFactory implements ComponentFactoryInterface
{
    public function build(ComponentFactoryProvider $provider, JsonObject $json): AbstractComponent
    {
        $component = new HtmlComponent($json->getString('content', ''));
        $component->setTextAlign($json->getString('textAlign', ''));

        return $component;
    }

    public function getComponentName(): string
    {
        return 'HtmlComponent';
    }
}
