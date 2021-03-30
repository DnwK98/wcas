<?php

namespace App\Page\Component\Html;

use App\Page\Component\AbstractComponent;

class HtmlComponent extends AbstractComponent
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function render(): string
    {
        return $this->content;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => 'HtmlComponent',
            'content' => $this->content
        ];
    }
}