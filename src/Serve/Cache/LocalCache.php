<?php

declare(strict_types=1);


namespace App\Serve\Cache;


class LocalCache implements CacheInterface
{
    public function getOrExecute(string $key, callable $function)
    {
        // TODO implement local cache
        return $function();
    }
}
