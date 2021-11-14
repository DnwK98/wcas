<?php

declare(strict_types=1);

namespace App\Page\Component\BackgroundImage;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentFactoryInterface;
use App\Page\Component\ComponentFactoryProvider;

class BackgroundImageComponentFactory implements ComponentFactoryInterface
{
    public function build(ComponentFactoryProvider $provider, JsonObject $json): AbstractComponent
    {
        $component = new BackgroundImageComponent();
        if ($json->isset('backgroundColor')) {
            $component->setBackgroundColor($json->getString('backgroundColor'));
        }

        if ($json->isset('backgroundImage')) {
            $component->setBackgroundImage($json->getString('backgroundImage'));
        }

        if ($json->isset('content')) {
            $contentComponent = $provider
                ->provide($json->getString('content.name'))
                ->build($provider, $json->getJson('content'));
            $component->setContent($contentComponent);
        }

        return $component;
    }

    public function getComponentName(): string
    {
        return 'BackgroundImageComponent';
    }
}
