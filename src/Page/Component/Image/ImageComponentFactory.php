<?php

declare(strict_types=1);

namespace App\Page\Component\Image;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentFactoryInterface;
use App\Page\Component\ComponentFactoryProvider;

class ImageComponentFactory implements ComponentFactoryInterface
{
    public function build(ComponentFactoryProvider $provider, JsonObject $json): AbstractComponent
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
