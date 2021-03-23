<?php


namespace App\Tests\User;


use App\Common\JsonObject\JsonObject;
use App\Tests\TestClass\FunctionalTestCase;
use App\User\Entity\User;

class UserApiTest extends FunctionalTestCase
{
    public function testRegisterUser()
    {
        $client = $this->client();
        $response = $client->request('POST', '/api/auth/register', [
            'email' => 'test@example.com',
            'password' => 'test-password'
        ]);

        $response = JsonObject::ofJson($client->getResponse()->getContent());

        $this->assertTrue($response->isset('userId'));
        $this->assertInstanceOf(
            User::class,
            $this->em->find(User::class, $response->getString('userId'))
        );
    }

    public function testRegisterUserValidation()
    {
        $client = $this->client();
        $client->request('POST', '/api/auth/register', [
            'email' => 'invalid email',
            'password' => 'short'
        ]);

        $response = JsonObject::ofJson($client->getResponse()->getContent());

        $this->assertFalse($response->isset('userId'));
        $this->assertTrue($response->isset('errors.email'));
        $this->assertTrue($response->isset('errors.password'));
    }

    public function testLogin()
    {
        $this->withUser();

        $client = $this->client();
        $client->request('POST', '/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $response = JsonObject::ofJson($client->getResponse()->getContent());

        $this->assertTrue($response->isset('token'));
    }

    public function testRefresh()
    {
        $this->withUser();

        $client = $this->client();
        $client->request('POST', '/api/auth/refresh', [],[], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->jwtToken()
        ]);

        $response = JsonObject::ofJson($client->getResponse()->getContent());

        $this->assertTrue($response->isset('token'));
    }

    public function testMe()
    {
        $this->withUser();
        $client = $this->client();
        $client->request('GET', '/api/me', [],[], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->jwtToken()
        ]);

        $response = JsonObject::ofJson($client->getResponse()->getContent());

        $this->assertTrue($response->isset('id'));
        $this->assertTrue($response->isset('email'));
        $this->assertTrue($response->isset('created'));

    }

    public function testUserList()
    {
        $this->dataGenerator->user()
            ->withEmail('administrator@example.com')
            ->withTestPassword()
            ->administrator()
            ->persistent();
        $this->em->flush();

        $client = $this->client();
        $client->request('GET', '/api/user', [],[], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->jwtToken('administrator@example.com')
        ]);

        $response = JsonObject::ofJson($client->getResponse()->getContent());

        foreach ($response as $userObject){
            $this->assertTrue($userObject->isset('id'));
            $this->assertTrue($userObject->isset('email'));
            $this->assertTrue($userObject->isset('created'));
        }
    }

    public function testUserListIsForbidden()
    {
        $this->withUser();

        $client = $this->client();
        $client->request('GET', '/api/user', [],[], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->jwtToken()
        ]);

        $response = JsonObject::ofJson($client->getResponse()->getContent());

        $this->assertEquals(403, $response->getInt('status'));
    }

    public function withUser(): void
    {
        $this->dataGenerator->user()
            ->withEmail('test@example.com')
            ->withTestPassword()
            ->persistent();
        $this->em->flush();
    }
}