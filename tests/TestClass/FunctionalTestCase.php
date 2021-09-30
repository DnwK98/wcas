<?php


namespace App\Tests\TestClass;


use App\Tests\DataGenerator\DataGenerator;
use App\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class FunctionalTestCase extends WebTestCase
{
    protected ?EntityManagerInterface $em;
    protected DataGenerator $dataGenerator;

    public function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        /** @var EntityManagerInterface $em */
        $em = $this->container()->get("doctrine.orm.entity_manager");
        $this->em = $em;

        $this->dataGenerator = new DataGenerator($this->em);

        $this->em->beginTransaction();
    }

    public function tearDown(): void
    {
        $this->em->rollback();
        $this->em->clear();
        $this->em->close();
        $this->em = null;
        parent::tearDown();
    }

    protected function client(): KernelBrowser
    {
        /** @var KernelBrowser $client */
        $client = $this->container()->get('test.client');
        $client->followRedirects(true);
        $client->disableReboot();

        return $client;
    }

    protected function request(): TestRequest
    {
        return new TestRequest($this->client());
    }

    protected function withUser(): User
    {
        /** @var User $user */
        $user = $this->dataGenerator->user()
            ->withEmail('test@example.com')
            ->withTestPassword()
            ->persistent()
            ->get();
        $this->em->flush();

        return $user;
    }

    protected function jwtToken(string $userEmail = 'test@example.com'): string
    {
        /** @var JWTTokenManagerInterface $jwtManager */
        $jwtManager = $this->container()->get(JWTTokenManagerInterface::class);

        /** @var User $user */
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $userEmail]);

        return $jwtManager->create($user);
    }

    protected function container(): ContainerInterface
    {
        return self::$container;
    }
}
