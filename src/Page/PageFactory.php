<?php

declare(strict_types=1);

namespace App\Page;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentFactoryProvider;

class PageFactory
{
    private ComponentFactoryProvider $componentFactoryProvider;

    public function __construct(ComponentFactoryProvider $componentFactoryProvider)
    {
        $this->componentFactoryProvider = $componentFactoryProvider;
    }

    public function build(JsonObject $jsonPage): AbstractComponent
    {
        $name = $jsonPage->getString('name');

        return $this->componentFactoryProvider
            ->provide($name)
            ->build($this->componentFactoryProvider, $jsonPage);
    }

    public function buildEmptyPage(): AbstractComponent
    {
        return $this->componentFactoryProvider->provide('PageComponent')->build(
            $this->componentFactoryProvider,
            JsonObject::ofArray([])
        );
    }
}
