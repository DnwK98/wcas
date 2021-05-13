<?php

declare(strict_types=1);

namespace App\Page\Component\BackgroundImage;

use App\Page\Component\AbstractComponent;
use App\Page\Component\None\NoneComponent;

class BackgroundImageComponent extends AbstractComponent
{
    private AbstractComponent $content;
    private string $backgroundColor = '#ffffff';
    private string $backgroundImage = '';

    public function __construct()
    {
        $this->content = new NoneComponent();
    }

    public function setContent(AbstractComponent $content): void
    {
        $this->content = $content;
    }

    public function setBackgroundColor(string $backgroundColor): void
    {
        $this->backgroundColor = $backgroundColor;
    }

    public function setBackgroundImage(string $backgroundImage): void
    {
        $this->backgroundImage = $backgroundImage;
    }

    public function render(): string
    {
        return $this->outputGet(function () {
            require __DIR__ . '/view.phtml';
        });
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => 'BackgroundImageComponent',
            'backgroundColor' => $this->backgroundColor,
            'backgroundImage' => $this->backgroundImage,
            'content' => $this->content->jsonSerialize(),
        ];
    }
}
