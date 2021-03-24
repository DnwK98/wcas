<?php

namespace App\Page\Component\None;

use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentInterface;

class NoneComponent extends AbstractComponent implements ComponentInterface
{
    public function render(): string
    {
        return '';
    }

    public function jsonSerialize()
    {
        return [
            'name' => 'NoneComponent',
        ];
    }
}