<?php

declare(strict_types=1);

namespace App\Tests\Website;

use App\Common\JsonObject\JsonObject;
use App\Common\Response\CreatedResponse;
use App\Common\Response\OkResponse;
use App\Tests\TestClass\FunctionalTestCase;
use App\Website\Entity\WebsitePage;

class WebsitePageApiTest extends FunctionalTestCase
{
    public function testCreatePage()
    {
        $user = $this->withUser();
        $website = $this->dataGenerator->website()->owner($user)->persistent()->get();
        $this->em->flush();
        $response = $this->request()
            ->method('POST')
            ->uri("/api/website/{$website->getId()}/page")
            ->addHeader('Authorization', 'Bearer ' . $this->jwtToken())
            ->parameters([
                'path' => 'index',
                'definition' => '{}',
            ])
            ->getResponse();

        $json = JsonObject::ofJson($response);

        $this->assertEquals(CreatedResponse::STATUS, $json->getInt('status'));
    }

    public function testEditPage()
    {
        $page = $this->withPage();
        $response = $this->request()
            ->method('POST')
            ->uri("/api/website/{$page->getWebsite()->getId()}/page/{$page->getId()}")
            ->addHeader('Authorization', 'Bearer ' . $this->jwtToken())
            ->parameters([
                'path' => 'index',
                'definition' => json_encode([
                    'name' => 'PageComponent',
                ]),
            ])
            ->getResponse();

        $json = JsonObject::ofJson($response);

        $this->assertEquals(OkResponse::STATUS, $json->getInt('status'));
        $this->assertEquals('PageComponent', $page->getDefinition()['name']);
    }

    public function testDeletePage()
    {
        $page = $this->withPage();
        $response = $this->request()
            ->method('DELETE')
            ->uri("/api/website/{$page->getWebsite()->getId()}/page/{$page->getId()}")
            ->addHeader('Authorization', 'Bearer ' . $this->jwtToken())
            ->getResponse();

        $json = JsonObject::ofJson($response);

        $this->assertEquals(OkResponse::STATUS, $json->getInt('status'));
        $this->assertEmpty($page->getWebsite()->getPages()->toArray());
    }

    public function testGetPage()
    {
        $page = $this->withPage();

        $response = $this->request()
            ->method('GET')
            ->uri("/api/website/{$page->getWebsite()->getId()}/page/{$page->getId()}")
            ->addHeader('Authorization', 'Bearer ' . $this->jwtToken())
            ->getResponse();

        $json = JsonObject::ofJson($response);

        $this->assertTrue($json->isset('data.id'));
        $this->assertTrue($json->isset('data.path'));
    }

    public function testGetPages()
    {
        $page = $this->withPage();

        $response = $this->request()
            ->method('GET')
            ->uri("/api/website/{$page->getWebsite()->getId()}/page")
            ->addHeader('Authorization', 'Bearer ' . $this->jwtToken())
            ->getResponse();

        $json = JsonObject::ofJson($response);

        foreach ($json->getJson('data') as $page) {
            $this->assertTrue($page->isset('id'));
            $this->assertTrue($page->isset('path'));
        }
    }

    private function withPage(): WebsitePage
    {
        $user = $this->withUser();
        $website = $this->dataGenerator->website()->owner($user)->persistent()->get();

        $page = $this->dataGenerator->websitePage()
            ->forWebsite($website)
            ->persistent()
            ->get();

        $this->em->flush();

        return $page;
    }
}
