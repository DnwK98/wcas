<?php

declare(strict_types=1);

namespace App\Page;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentBuilderProvider;

class PageBuilder
{
    private ComponentBuilderProvider $componentBuilderProvider;

    public function __construct(ComponentBuilderProvider $componentBuilderProvider)
    {
        $this->componentBuilderProvider = $componentBuilderProvider;
    }

    public function build(JsonObject $jsonPage): AbstractComponent
    {
        $name = $jsonPage->getString('name');

        return $this->componentBuilderProvider
            ->provide($name)
            ->build($this->componentBuilderProvider, $jsonPage);
    }

    public function buildEmptyPage(): AbstractComponent
    {
        return $this->componentBuilderProvider->provide('PageComponent')->build(
            $this->componentBuilderProvider,
            JsonObject::ofArray([])
        );
    }
}
