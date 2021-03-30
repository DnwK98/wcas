<?php


namespace App\Tests\Domain;


use App\Common\JsonObject\JsonObject;
use App\Common\Response\BadRequestResponse;
use App\Common\Response\CreatedResponse;
use App\Tests\TestClass\FunctionalTestCase;

class DomainApiTest extends FunctionalTestCase
{

    public function testGetDomainList()
    {
        $user = $this->dataGenerator->user()->persistent()->get();
        $this->dataGenerator->domain()->withDomain('test1.com')->owner($user)->persistent();
        $this->dataGenerator->domain()->withDomain('test2.com')->owner(null)->persistent();
        $this->em->flush();

        $response = $this->request()
            ->uri('/api/domain')
            ->addHeader('Authorization', 'Bearer ' . $this->jwtToken())
            ->getResponse();

        $json = JsonObject::ofJson($response);

        $this->assertCount(2, $json->getArray('data'));
    }
    public function testAddDomainWhichIsLikeExistingOne()
    {
        $this->checkDomainRequestResponse(
            'test-abc.pl',
            'abc.pl',
            CreatedResponse::STATUS
        );
    }

    public function testAddDomainWhichParentExists()
    {
        $this->checkDomainRequestResponse(
            'test.abc.pl',
            'abc.pl',
            BadRequestResponse::STATUS
        );
    }

    public function testAddDomainWhichChildExists()
    {
        $this->checkDomainRequestResponse(
            'abc.pl',
            'abc.pl',
            BadRequestResponse::STATUS
        );
    }

    public function testAddDomainWhichExists()
    {
        $this->checkDomainRequestResponse(
            'abc.pl',
            'test.abc.pl',
            BadRequestResponse::STATUS
        );
    }

    private function checkDomainRequestResponse(string $existing, string $created, int $responseCode)
    {
        $this->withUser();
        $this->dataGenerator->domain()->withDomain($existing)->persistent();
        $this->em->flush();

        $response = $this->request()
            ->method('POST')
            ->uri('/api/domain')
            ->addHeader('Authorization', 'Bearer ' . $this->jwtToken())
            ->parameters(['domain' => $created])
            ->getResponse();

        $json = JsonObject::ofJson($response);

        $this->assertEquals($responseCode, $json->getInt('status'));
    }
}