<?php

declare(strict_types=1);

namespace App\Domain;

use App\Common\JsonObject\JsonObject;
use App\Domain\Entity\Domain;
use App\Domain\Entity\Repository\DomainRepository;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Throwable;

class DomainVerifier
{
    private EntityManagerInterface $em;
    private DomainRepository $domainRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        /** @var DomainRepository $repo */
        $repo = $this->em->getRepository(Domain::class);
        $this->domainRepository = $repo;
    }

    public function verifyAllDomains()
    {
        $domains = $this->domainRepository->findAll();
        foreach ($domains as $domain) {
            $this->verifyDomain($domain);
        }
    }

    public function verifyDomain(Domain $domain)
    {
        $hash = md5((string)rand(0, 9999999));
        $verificationUri = $domain->getDomain() . "/api/domain/verification/$hash";

        try {
            $client = new Client();
            $response = JsonObject::ofJson($client->get($verificationUri)->getBody()->getContents());
            if ($response->isset('data.verification')) {
                $verification = $response->getString('data.verification');

                // TODO crypto verification
                if ($verification === $hash) {
                    $domain->setVerified(true);
                } else {
                    $domain->setVerified(false);
                }
            }
        } catch (Throwable $e) {
            $domain->setVerified(false);
        }

        $this->domainRepository->save($domain);
    }
}
