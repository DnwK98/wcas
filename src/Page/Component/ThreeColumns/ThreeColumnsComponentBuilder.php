<?php

namespace App\Page\Component\ThreeColumns;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\ComponentBuilderInterface;
use App\Page\Component\ComponentBuilderProvider;
use App\Page\Component\ComponentInterface;

class ThreeColumnsComponentBuilder implements ComponentBuilderInterface
{
    public function build(ComponentBuilderProvider $provider, JsonObject $json): ComponentInterface
    {
        /** @var ComponentInterface[] $columns */
        $columns = [];
        foreach (ThreeColumnsComponent::COLUMN_NAMES as $column){
            $columns[] = $provider
                ->provide($json->getJson($column)->getString('name'))
                ->build($provider, $json->getJson($column));
        }

        return new ThreeColumnsComponent(...$columns);
    }

    public function getComponentName(): string
    {
        return 'ThreeColumnsComponent';
    }
}