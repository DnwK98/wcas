<?php

declare(strict_types=1);

namespace App\Serve\Cache;

class LocalCache extends RedisCache implements CacheInterface
{
    const TTL = 120;
}
