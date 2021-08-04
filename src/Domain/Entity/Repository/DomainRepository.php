<?php

declare(strict_types=1);

namespace App\Domain\Entity\Repository;

use App\Domain\Entity\Domain;
use App\User\Entity\User;
use App\User\UserPermittedQuery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Domain>
 *
 * @method Domain|null find($id, $lockMode = null, $lockVersion = null)
 * @method Domain|null findOneBy(array $criteria, array $orderBy = null)
 * @method Domain[]    findAll()
 * @method Domain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @psalm-method list<Domain>  findAll()
 * @psalm-method list<Domain>  findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DomainRepository extends ServiceEntityRepository
{
    use UserPermittedQuery;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Domain::class);
    }

    public function findByDomain(string $value): ?Domain
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.domain = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $value
     *
     * @return Domain[]
     */
    public function searchByMainDomain(string $value): array
    {
        $qb = $this->createQueryBuilder('d');

        return $qb
            ->andWhere($qb->expr()->like('d.domain', $qb->expr()->literal("%$value")))
            ->getQuery()
            ->getResult();
    }

    public function findForUser(User $owner)
    {
        $qb = $this->createQueryBuilder('d');
        $this->restrictQueryBuilder($qb, $owner);

        return $qb
            ->getQuery()
            ->getResult();
    }

    public function save(Domain $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function removeUserDomains(string $id)
    {
        $qb = $this->createQueryBuilder('d');
        $qb->delete()
            ->where($qb->expr()->eq('d.owner', ':id'))
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }
}
