<?php

declare(strict_types=1);

namespace App\Serve\Cache;

class StackedCache implements CacheInterface
{
    /** @var CacheInterface[] */
    private array $stacked;

    public function __construct(array $stacked)
    {
        $this->stacked = $stacked;
    }

    public function getOrExecute(string $key, callable $function)
    {
        /** @var CacheInterface[] $stack */
        $stack = [];
        foreach ($this->stacked as $stackedItem) {
            $stack[] = $stackedItem;
        }

        $executeFromStack = function ($self) use ($key, &$stack, $function) {
            if (empty($stack)) {
                return $function();
            }

            $cache = array_shift($stack);

            return $cache->getOrExecute($key, function () use ($self) {
                return $self($self);
            });
        };

        return $executeFromStack($executeFromStack);
    }
}
