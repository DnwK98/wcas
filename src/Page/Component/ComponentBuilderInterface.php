<?php

namespace App\Page\Component;

use App\Common\JsonObject\JsonObject;

interface ComponentBuilderInterface
{
    public function build(ComponentBuilderProvider $provider, JsonObject $json): AbstractComponent;

    public function getComponentName(): string;
}