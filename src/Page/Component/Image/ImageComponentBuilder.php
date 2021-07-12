<?php

declare(strict_types=1);


namespace App\Page\Component\Image;


use App\Common\JsonObject\JsonObject;
use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentBuilderInterface;
use App\Page\Component\ComponentBuilderProvider;

class ImageComponentBuilder implements ComponentBuilderInterface
{
    public function build(ComponentBuilderProvider $provider, JsonObject $json): AbstractComponent
    {
        $component = new ImageComponent();
        $component->setImage($json->getString('image', ''));

        return $component;
    }

    public function getComponentName(): string
    {
        return 'ImageComponent';
    }
}
