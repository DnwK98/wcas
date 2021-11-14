<?php

declare(strict_types=1);

namespace App\Page\Component\None;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentFactoryInterface;
use App\Page\Component\ComponentFactoryProvider;

class NoneComponentFactory implements ComponentFactoryInterface
{
    public function build(ComponentFactoryProvider $provider, JsonObject $json): AbstractComponent
    {
        return new NoneComponent();
    }

    public function getComponentName(): string
    {
        return 'NoneComponent';
    }
}
