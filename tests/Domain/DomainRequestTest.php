<?php


namespace App\Tests\Domain;


use App\Domain\Api\Form\DomainRequest;
use App\Tests\TestClass\UnitTestCase;

class DomainRequestTest extends UnitTestCase
{
    /**
     * @param $requestedDomain
     * @param $valid
     *
     * @dataProvider domainProvider
     */
    public function testDomainIsValid($requestedDomain, $valid)
    {
        $domainRequest = new DomainRequest();
        $domainRequest->domain = $requestedDomain;

        $this->assertEquals($valid, $domainRequest->isDomainValid(), "Failed for domain $requestedDomain");
    }

    public function domainProvider(): array
    {
        return [
            ['test.pl', true],
            ['test.abc.pl', true],
            ['test-abc.pl', true],
            ['very.long.with.multiple.subdomain.test.abc.pl', true],
            ['test.abc.p-l', false],
            ['test.abc.pl/', false],
            ['test.abc.p2', false],
            ['test.abc.p2l', false],
            ['test.abc.pl?a=b', false],
            ['test.abc.pl?', false],
            ['http://test.abc.pl?', false],
            ['https://test.abc.pl?', false],
            ['https://test', false],
            ['test', false],
        ];
    }
}