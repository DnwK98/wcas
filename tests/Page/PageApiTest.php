<?php

declare(strict_types=1);

namespace App\Tests\Page;

use App\Tests\TestClass\FunctionalTestCase;

class PageApiTest extends FunctionalTestCase
{
    public function testCreatesPreviewForJsonRequest()
    {
        $this->withUser();
        $response = $this->request()
            ->method('POST')
            ->uri('/api/page/preview')
            ->addHeader('Authorization', 'Bearer ' . $this->jwtToken())
            ->json([
                'name' => 'PageComponent',
                'children' => [
                    ['name' => 'NoneComponent'],
                    ['name' => 'NoneComponent'],
                ],
            ])
            ->getResponse();

        $this->assertStringContainsString('html', $response);
    }

    public function testCreatesEmptyPreviewForAnyValidJson()
    {
        $this->withUser();
        $response = $this->request()
            ->method('POST')
            ->uri('/api/page/preview')
            ->addHeader('Authorization', 'Bearer ' . $this->jwtToken())
            ->json(['any-value'])
            ->getResponse();

        $this->assertEmpty($response);
    }

    public function testRespondsWithBadRequestOnInvalidJson()
    {
        $this->withUser();
        $response = $this->request()
            ->method('POST')
            ->uri('/api/page/preview')
            ->addHeader('Authorization', 'Bearer ' . $this->jwtToken())
            ->getResponse();

        $this->assertStringContainsString('Your request is invalid', $response);
    }
}
