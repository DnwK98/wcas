<?php

declare(strict_types=1);


namespace App\Serve\Cache;


class RedisCache implements CacheInterface
{
    public function getOrExecute(string $key, callable $function)
    {
        // TODO implement redis cache
        return $function();
    }
}
