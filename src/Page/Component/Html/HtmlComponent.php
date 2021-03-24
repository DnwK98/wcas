<?php

namespace App\Page\Component\Html;

use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentInterface;

class HtmlComponent extends AbstractComponent implements ComponentInterface
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

    public function jsonSerialize()
    {
        return [
            'name' => 'HtmlComponent',
            'content' => $this->content
        ];
    }
}