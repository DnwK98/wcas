<?php

declare(strict_types=1);

namespace App\Page\Component\Margins;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentBuilderInterface;
use App\Page\Component\ComponentBuilderProvider;

class MarginsComponentBuilder implements ComponentBuilderInterface
{
    public function build(ComponentBuilderProvider $provider, JsonObject $json): AbstractComponent
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
