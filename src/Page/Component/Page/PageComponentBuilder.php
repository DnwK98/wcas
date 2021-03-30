<?php

namespace App\Page\Component\Page;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\ComponentBuilderInterface;
use App\Page\Component\ComponentBuilderProvider;
use App\Page\Component\AbstractComponent;

class PageComponentBuilder implements ComponentBuilderInterface
{
    public function build(ComponentBuilderProvider $provider, JsonObject $json): AbstractComponent
    {
        /** @var AbstractComponent[] $columns */
        $components = [];
        foreach ($json->getJson('children') as $child){
            $components[] = $provider
                ->provide($child->getString('name'))
                ->build($provider, $child);
        }

        return new PageComponent($components);
    }

    public function getComponentName(): string
    {
        return 'PageComponent';
    }
}