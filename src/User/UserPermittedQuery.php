<?php

namespace App\User;

use App\User\Entity\User;
use Doctrine\ORM\QueryBuilder;

trait UserPermittedQuery
{
    public function restrictQueryBuilder(QueryBuilder $qb, ?User $user): void
    {
        $aliases = $qb->getRootAliases();
        $alias = end($aliases);

        if (!in_array(UserRoles::ADMINISTRATOR, $user->getRoles())) {
            /* @psalm-suppress TooManyArguments */
            $qb
                ->andWhere($qb->expr()->orX(
                    $qb->expr()->eq("{$alias}.owner", ':owner'),
                    $qb->expr()->isNull("{$alias}.owner")
                ))
                ->setParameter('owner', $user);
        }
    }
}
