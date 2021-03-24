<?php

namespace App\Page\Component;

use JsonSerializable;

interface ComponentInterface extends JsonSerializable
{
    public function render(): string;
}