<?php

declare(strict_types=1);


namespace App\Website;


class StatusEnum
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';

    public static function list(): array
    {
        return [self::ACTIVE, self::INACTIVE];
    }
}
