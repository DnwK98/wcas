<?php

declare(strict_types=1);

namespace App\Page\Component\Page;

use App\Common\Doctrine\Collection\Collection;
use App\Page\Component\AbstractComponent;

class PageComponent extends AbstractComponent
{
    /** @var AbstractComponent[] */
    private array $children;

    public function __construct(array $children)
    {
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
            'children' => Collection::Collect($this->children)
                ->map(function (AbstractComponent $component): array {
                    return $component->jsonSerialize();
                })
                ->toArray(),
        ];
    }
}
