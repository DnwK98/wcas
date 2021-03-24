<?php

namespace App\Page\Component;

use App\Common\JsonObject\JsonObject;

interface ComponentBuilderInterface
{
    public function build(ComponentBuilderProvider $provider, JsonObject $json): ComponentInterface;

    public function getComponentName(): string;
}