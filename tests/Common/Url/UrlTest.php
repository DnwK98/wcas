<?php

declare(strict_types=1);


namespace App\Tests\Common\Url;


use App\Common\Url\Url;
use App\Tests\TestClass\UnitTestCase;

class UrlTest extends UnitTestCase
{
    /**
     * @dataProvider urlsToParse
     *
     * @param string $url
     * @param string $protocol
     * @param string $domain
     * @param string $path
     * @param string $query
     */
    public function testCreateUrlFromString(string $url, string $protocol, string $domain, string  $path, string $query)
    {
        $objectUrl = Url::fromString($url);
        $this->assertSame($protocol, $objectUrl->getProtocol());
        $this->assertSame($domain, $objectUrl->getDomain());
        $this->assertSame($path, $objectUrl->getPath());
        $this->assertSame($query, $objectUrl->getQuery());
        $this->assertSame(trim($url), $objectUrl->getUrl());
    }

    public function urlsToParse(): array
    {
        return [
            ["\texample.com", '', 'example.com', '', ''],
            [" example.com", '', 'example.com', '', ''],
            ['example.com', '', 'example.com', '', ''],
            ['www.example.com', '', 'www.example.com', '', ''],
            ['www.example.com/a', '', 'www.example.com', '/a', ''],
            ['bad-protocol://www.example.com', 'bad-protocol', 'www.example.com', '', ''],
            ['https://www.example.com', 'https', 'www.example.com', '', ''],
            ['http://www.example.com/abc/de', 'http', 'www.example.com', '/abc/de', ''],
            ['https://www.example.com?a=b', 'https', 'www.example.com', '', 'a=b'],
            ['https://www.example.com/?a=b', 'https', 'www.example.com', '/', 'a=b'],
            ['a://b/c?d=e', 'a', 'b', '/c', 'd=e'],
            // Invalid urls
            ['https://example.com/https://abc.pl', 'https', 'example.com', '/https://abc.pl', ''],
            ['https://example.com?a=b?c=d', 'https', 'example.com', '', 'a=b?c=d'],
        ];
    }
}
