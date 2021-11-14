<?php

declare(strict_types=1);

namespace App\Page\Component\TwoColumns;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentFactoryInterface;
use App\Page\Component\ComponentFactoryProvider;

class TwoColumnsComponentFactory implements ComponentFactoryInterface
{
    public function build(ComponentFactoryProvider $provider, JsonObject $json): AbstractComponent
    {
        /** @var AbstractComponent[] $columns */
        $columns = [];
        foreach (TwoColumnsComponent::COLUMN_NAMES as $column) {
            $columns[] = $provider
                ->provide($json->getJson($column)->getString('name'))
                ->build($provider, $json->getJson($column));
        }

        return new TwoColumnsComponent(...$columns);
    }

    public function getComponentName(): string
    {
        return 'TwoColumnsComponent';
    }
}
