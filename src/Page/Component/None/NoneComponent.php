<?php

declare(strict_types=1);

namespace App\Page\Component\None;

use App\Page\Component\AbstractComponent;

class NoneComponent extends AbstractComponent
{
    public function render(): string
    {
        return '';
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => 'NoneComponent',
        ];
    }
}
