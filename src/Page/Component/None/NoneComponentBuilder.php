<?php


namespace App\Page\Component\None;


use App\Common\JsonObject\JsonObject;
use App\Page\Component\ComponentBuilderInterface;
use App\Page\Component\ComponentBuilderProvider;
use App\Page\Component\ComponentInterface;

class NoneComponentBuilder implements ComponentBuilderInterface
{
    public function build(ComponentBuilderProvider $provider, JsonObject $json): ComponentInterface
    {
        return new NoneComponent();
    }

    public function getComponentName(): string
    {
        return 'NoneComponent';
    }
}