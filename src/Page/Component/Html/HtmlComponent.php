<?php

declare(strict_types=1);

namespace App\Page\Component\Html;

use App\Page\Component\AbstractComponent;

class HtmlComponent extends AbstractComponent
{
    private string $content;
    private string $textAlign = 'left';

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function render(): string
    {
        return $this->outputGet(function () {
            require __DIR__ . '/view.phtml';
        });
    }

    public function getTextAlignClass(): string
    {
        switch ($this->textAlign) {
            case 'center':
                return 'text-center';
            case 'right':
                return 'text-end';
            case 'justify':
                return 'text-justify';
            default:
                return '';
        }
    }

    public function setTextAlign(string $textAlign): void
    {
        $this->textAlign = $textAlign;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => 'HtmlComponent',
            'content' => $this->content,
            'textAlign' => $this->textAlign,
        ];
    }
}
