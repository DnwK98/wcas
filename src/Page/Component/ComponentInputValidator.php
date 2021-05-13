<?php

declare(strict_types=1);

namespace App\Page\Component;

class ComponentInputValidator
{
    public static function color(string $color): bool
    {
        preg_match('/^#(?:[0-9a-fA-F]{3}){1,2}$/', $color, $matches);

        return !empty($matches);
    }
}
