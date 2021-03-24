<?php


namespace App\Page;


use App\Common\JsonObject\JsonObject;
use App\Page\Component\ComponentBuilderProvider;
use App\Page\Component\ComponentInterface;

class PageBuilder
{
    private ComponentBuilderProvider $componentBuilderProvider;

    public function __construct(ComponentBuilderProvider $componentBuilderProvider)
    {
        $this->componentBuilderProvider = $componentBuilderProvider;
    }

    public function build(JsonObject $jsonPage): ComponentInterface
    {
        $name = $jsonPage->getString('name');

        return $this->componentBuilderProvider
            ->provide($name)
            ->build($this->componentBuilderProvider, $jsonPage);
    }
}