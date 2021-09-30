<?php

declare(strict_types=1);

namespace App\Page\Component\TwoColumns;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentBuilderInterface;
use App\Page\Component\ComponentBuilderProvider;

class TwoColumnsComponentBuilder implements ComponentBuilderInterface
{
    public function build(ComponentBuilderProvider $provider, JsonObject $json): AbstractComponent
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
