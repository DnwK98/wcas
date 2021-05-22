<?php

declare(strict_types=1);

namespace App\Page\Component\Margins;

use App\Page\Component\AbstractComponent;

class MarginsComponent extends AbstractComponent
{
    private string $marginTop = 'none';
    private string $marginBottom = 'none';
    private string $marginLeftRight = 'none';
    private AbstractComponent $content;

    public function __construct(AbstractComponent $content)
    {
        $this->content = $content;
    }

    public function setMarginTop(string $marginTop): void
    {
        $this->marginTop = $marginTop;
    }

    public function setMarginBottom(string $marginBottom): void
    {
        $this->marginBottom = $marginBottom;
    }

    public function setMarginLeftRight(string $marginLeftRight): void
    {
        $this->marginLeftRight = $marginLeftRight;
    }

    public function render(): string
    {
        return $this->outputGet(function () {
            $contentClass = MarginSize::columnContentClass($this->marginLeftRight);
            $marginLeftRightClass = MarginSize::columnMarginClass($this->marginLeftRight);
            $marginTopStyle = MarginSize::heightMarginStyle($this->marginTop);
            $marginBottomStyle = MarginSize::heightMarginStyle($this->marginBottom);

            require __DIR__ . '/view.phtml';
        });
    }

    public function jsonSerialize()
    {
        return [
            'name' => 'MarginsComponent',
            'marginTop' => $this->marginTop,
            'marginBottom' => $this->marginBottom,
            'marginLeftRight' => $this->marginLeftRight,
            'content' => $this->content->jsonSerialize(),
        ];
    }
}
