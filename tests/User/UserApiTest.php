<?php

declare(strict_types=1);

namespace App\Tests\User;

use App\Common\JsonObject\JsonObject;
use App\Tests\TestClass\FunctionalTestCase;
use App\User\Entity\User;

class UserApiTest extends FunctionalTestCase
{
    public function testRegisterUser()
    {
        $response = JsonObject::ofJson($this->request()
            ->method('POST')
            ->uri('/api/auth/register')
            ->parameters([
                'email' => 'test@example.com',
                'password' => 'test-password',
            ])
            ->getResponse()
        );

        $this->assertTrue($response->isset('userId'));
        $this->assertInstanceOf(
            User::class,
            $this->em->find(User::class, $response->getString('userId'))
        );
    }

    public function testRegisterUserValidation()
    {
        $response = JsonObject::ofJson($this->request()
            ->method('POST')
            ->uri('/api/auth/register')
            ->parameters([
                'email' => 'invalid email',
                'password' => 'short',
            ])
            ->getResponse()
        );

        $this->assertFalse($response->isset('userId'));
        $this->assertTrue($response->isset('errors.email'));
        $this->assertTrue($response->isset('errors.password'));
    }

    public function testLogin()
    {
        $this->withUser();
        $response = JsonObject::ofJson($this->request()
            ->method('POST')
            ->uri('/api/auth/login')
            ->parameters([
                'email' => 'test@example.com',
                'password' => 'password',
            ])
            ->getResponse()
        );

        $this->assertTrue($response->isset('token'));
    }

    public function testRefresh()
    {
        $this->withUser();
        $response = JsonObject::ofJson($this->request()
            ->method('POST')
            ->uri('/api/auth/refresh')
            ->addHeader('Authorization', 'Bearer ' . $this->jwtToken())
            ->getResponse()
        );

        $this->assertTrue($response->isset('token'));
    }

    public function testMe()
    {
        $this->withUser();
        $response = JsonObject::ofJson($this->request()
            ->uri('/api/me')
            ->addHeader('Authorization', 'Bearer ' . $this->jwtToken())
            ->getResponse()
        );

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

        $response = JsonObject::ofJson($this->request()
            ->uri('/api/user')
            ->addHeader('Authorization', 'Bearer ' . $this->jwtToken('administrator@example.com'))
            ->getResponse()
        );

        foreach ($response as $userObject) {
            $this->assertTrue($userObject->isset('id'));
            $this->assertTrue($userObject->isset('email'));
            $this->assertTrue($userObject->isset('created'));
        }
    }

    public function testUserListIsForbiddenForNonAdminUser()
    {
        $this->withUser();
        $response = JsonObject::ofJson($this->request()
            ->uri('/api/user')
            ->addHeader('Authorization', 'Bearer ' . $this->jwtToken())
            ->getResponse()
        );

        $this->assertEquals(403, $response->getInt('status'));
    }
}
