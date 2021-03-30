<?php

declare(strict_types=1);

namespace App\Page\Component\None;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentBuilderInterface;
use App\Page\Component\ComponentBuilderProvider;

class NoneComponentBuilder implements ComponentBuilderInterface
{
    public function build(ComponentBuilderProvider $provider, JsonObject $json): AbstractComponent
    {
        return new NoneComponent();
    }

    public function getComponentName(): string
    {
        return 'NoneComponent';
    }
}
