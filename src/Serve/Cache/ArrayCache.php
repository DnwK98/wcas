<?php

declare(strict_types=1);

namespace App\Serve\Cache;

class ArrayCache implements CacheInterface
{
    const TTL = 30;

    const ELEMENT = 0;
    const EXPIRE = 1;

    private array $cached = [];

    public function getOrExecute(string $key, callable $function)
    {
        if (!isset($this->cached[$key]) || $this->cached[$key][self::EXPIRE] < time()) {
            $this->cached[$key][self::ELEMENT] = $function();
            $this->cached[$key][self::EXPIRE] = time() + self::TTL;
        }

        return $this->cached[$key][self::ELEMENT];
    }
}
