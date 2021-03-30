<?php

declare(strict_types=1);

namespace App\Website\Entity\Repository;

use App\User\Entity\User;
use App\User\UserPermittedQuery;
use App\Website\Entity\Website;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Website>
 *
 * @method Website|null find($id, $lockMode = null, $lockVersion = null)
 * @method Website|null findOneBy(array $criteria, array $orderBy = null)
 * @method Website[]    findAll()
 * @method Website[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @psalm-method list<Website>  findAll()
 * @psalm-method list<Website>  findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebsiteRepository extends ServiceEntityRepository
{
    use UserPermittedQuery;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Website::class);
    }

    public function findForUser(User $owner)
    {
        $qb = $this->createQueryBuilder('w');
        $this->restrictQueryBuilder($qb, $owner);

        return $qb->getQuery()->getResult();
    }

    public function save(Website $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
