<?php

declare(strict_types=1);


namespace App\Tests\Serve\Cache;


use App\Serve\Cache\ArrayCache;
use App\Serve\Cache\StackedCache;
use App\Tests\TestClass\UnitTestCase;

class StackedCacheTest extends UnitTestCase
{
    public function testStackedCacheCallsFunctionAndSavesItemInEveryCache()
    {
        $cache1 = new ArrayCache();
        $cache2 = new ArrayCache();
        $cache3 = new ArrayCache();
        $stackedCache = new StackedCache([$cache1, $cache2, $cache3]);

        $failCallable = function () {
            $this->fail("Unexpected function call");
        };

        $response = $stackedCache->getOrExecute('test1', function (){
            return 'value1';
        });

        $this->assertEquals('value1', $response);
        $this->assertEquals('value1', $cache1->getOrExecute('test1', $failCallable));
        $this->assertEquals('value1', $cache2->getOrExecute('test1', $failCallable));
        $this->assertEquals('value1', $cache3->getOrExecute('test1', $failCallable));
    }

    public function testStackedCacheReturnsItemFromFirstContainingCache()
    {
        $cache1 = new ArrayCache();
        $cache2 = new ArrayCache();
        $cache3 = new ArrayCache();
        $stackedCache = new StackedCache([$cache1, $cache2, $cache3]);

        $failCallable = function () {
            $this->fail("Unexpected function call");
        };

        $cache2->getOrExecute('test1', function (){
            return 'value';
        });

        $response = $stackedCache->getOrExecute('test1', $failCallable);

        $this->assertEquals('value', $response);
        $this->assertEquals('value', $cache1->getOrExecute('test1', $failCallable));

        $executed = false;
        $cache3->getOrExecute('test1', function () use (&$executed){
            $executed = true;
        });

        $this->assertTrue($executed, "Cache 3 already has item");
    }
}
