<?php

declare(strict_types=1);

namespace App\Page\Component;

use App\Common\JsonObject\JsonObject;

interface ComponentFactoryInterface
{
    public function build(ComponentFactoryProvider $provider, JsonObject $json): AbstractComponent;

    public function getComponentName(): string;
}
