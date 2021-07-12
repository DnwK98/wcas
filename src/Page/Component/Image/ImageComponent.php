<?php

declare(strict_types=1);


namespace App\Page\Component\Image;


use App\Page\Component\AbstractComponent;

class ImageComponent extends AbstractComponent
{
    private string $image;

    public function render(): string
    {
        return $this->outputGet(function () {
            require __DIR__ . '/view.phtml';
        });
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => 'ImageComponent',
            'image' => $this->image,
        ];
    }
}
