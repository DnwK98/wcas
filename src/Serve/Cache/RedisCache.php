<?php

declare(strict_types=1);

namespace App\Serve\Cache;

use Psr\Log\LoggerInterface;

class RedisCache implements CacheInterface
{
    const TTL = 5 * 60;

    private \Redis $redis;
    private LoggerInterface $log;

    public function __construct(\Redis $redis, LoggerInterface $log)
    {
        $this->redis = $redis;
        $this->log = $log;
    }

    public function getOrExecute(string $key, callable $function)
    {
        $k = __METHOD__ . '__' . $key;

        try {
            $value = $this->redis->get($k);
            if (!$value) {
                $value = $function();
                $this->redis->setex($k, static::TTL, $value);
            }

            return $value;
        } catch (\Throwable $e) {
            $this->log->error('Error in ' . static::class . '. Serving from source...', ['e' => $e]);
        }

        return $function();
    }
}
