<?php

declare(strict_types=1);

namespace App\Page\Component\Page;

use App\Common\Doctrine\Collection\Collection;
use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentInputValidator;

class PageComponent extends AbstractComponent
{
    private string $backgroundColor;

    /** @var AbstractComponent[] */
    private array $children;

    public function __construct(array $children, string $backgroundColor = '#ffffff')
    {
        $this->backgroundColor = $backgroundColor;
        $this->children = Collection::Collect($children)
            ->map(function (AbstractComponent $component) {
                return $component;
            })
            ->toArray();
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
            'name' => 'PageComponent',
            'backgroundColor' => $this->backgroundColor,
            'children' => Collection::Collect($this->children)
                ->map(function (AbstractComponent $component): array {
                    return $component->jsonSerialize();
                })
                ->toArray(),
        ];
    }

    public function setBackgroundColor(string $color)
    {
        if (ComponentInputValidator::color($color)) {
            $this->backgroundColor = $color;
        }
    }
}
