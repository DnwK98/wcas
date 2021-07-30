<?php

declare(strict_types=1);


namespace App\Serve\Cache;


interface CacheInterface
{
    public function getOrExecute(string $key, callable $function);
}
