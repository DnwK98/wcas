<?php

declare(strict_types=1);

namespace App\Page\Component\Margins;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentFactoryInterface;
use App\Page\Component\ComponentFactoryProvider;

class MarginsComponentFactory implements ComponentFactoryInterface
{
    public function build(ComponentFactoryProvider $provider, JsonObject $json): AbstractComponent
    {
        $contentComponent = $provider
            ->provide($json->getString('content.name', 'NoneComponent'))
            ->build($provider, $json->getJson('content'));

        $component = new MarginsComponent($contentComponent);
        $component->setMarginTop($json->getString('marginTop', 'none'));
        $component->setMarginBottom($json->getString('marginBottom', 'none'));
        $component->setMarginLeftRight($json->getString('marginLeftRight', 'none'));

        return $component;
    }

    public function getComponentName(): string
    {
        return 'MarginsComponent';
    }
}
