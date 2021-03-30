<?php


namespace App\Domain;


use App\Common\Doctrine\Collection\Collection;
use App\Domain\Entity\Domain;
use App\Domain\Entity\Repository\DomainRepository;
use App\User\Entity\User;

class DomainValidator
{
    private DomainRepository $domainRepository;

    public function __construct(DomainRepository $domainRepository)
    {
        $this->domainRepository = $domainRepository;
    }

    public function domainDoesNotOverlapsExistingOne(string $domain): bool
    {
        $parts = explode('.', $domain);

        $suffix = array_pop($parts);
        $mainDomain = array_pop($parts);

        $domains = $this->domainRepository->searchByMainDomain("{$mainDomain}.{$suffix}");

        foreach ($domains as $foundDomain){
            if($foundDomain->getDomain() === $domain){
                return false;
            }
            if(str_contains($foundDomain->getDomain(), "." . $domain)){
                return false;
            }
            if(str_contains($domain, "." . $foundDomain->getDomain())){
                return false;
            }
        }

        return true;
    }

    public function hasValidDomainForUser(string $url, User $user): bool
    {
        return Collection::Collect($this->domainRepository->findForUser($user))
            ->exists(function (int $key, Domain $domain) use($url){
                return str_ends_with($url, $domain->getDomain());
            });
    }
}