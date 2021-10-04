<?php

declare(strict_types=1);

namespace App\Serve\Cache;

class NullCache implements CacheInterface
{
    public function getOrExecute(string $key, callable $function)
    {
        return $function();
    }
}
