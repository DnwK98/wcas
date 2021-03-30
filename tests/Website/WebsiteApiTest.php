<?php


namespace App\Tests\Website;


use App\Common\JsonObject\JsonObject;
use App\Common\Response\CreatedResponse;
use App\Tests\TestClass\FunctionalTestCase;

class WebsiteApiTest extends FunctionalTestCase
{
    public function testCreateWebpage()
    {
        $user = $this->withUser();
        $this->dataGenerator->domain()->withDomain('test.com')->owner($user)->persistent();
        $this->em->flush();
        $response = $this->request()
            ->method('POST')
            ->uri('/api/website')
            ->addHeader('Authorization', 'Bearer ' . $this->jwtToken())
            ->parameters(['url' => 'abc.test.com'])
            ->getResponse();

        $json = JsonObject::ofJson($response);

        $this->assertEquals(CreatedResponse::STATUS, $json->getInt('status'));
    }
}