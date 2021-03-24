<?php

namespace App\Page\Component\Page;

use App\Common\Doctrine\Collection\Collection;
use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentInterface;

class PageComponent extends AbstractComponent implements ComponentInterface
{
    /** @var ComponentInterface[] */
    private array $children;

    public function __construct(array $children)
    {
        $this->children = Collection::Collect($children)
            ->map(function (ComponentInterface $component) {
                return $component;
            })
            ->toArray();
    }

    public function render(): string
    {
        return $this->outputGet(function (){
            require __DIR__ . '/view.phtml';
        });
    }

    public function jsonSerialize()
    {
        return [
            'name' => 'PageComponent',
            'children' => Collection::Collect($this->children)
                ->map(function (ComponentInterface $component): array {
                    return $component->jsonSerialize();
                })
                ->toArray()
        ];
    }
}