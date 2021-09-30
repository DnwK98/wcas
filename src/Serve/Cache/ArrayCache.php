<?php

declare(strict_types=1);

namespace App\Serve\Cache;

class ArrayCache implements CacheInterface
{
    private array $cached = [];

    public function getOrExecute(string $key, callable $function)
    {
        if (!isset($this->cached[$key])) {
            $this->cached[$key] = $function();
        }

        return $this->cached[$key];
    }
}
