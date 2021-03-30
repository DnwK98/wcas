<?php

declare(strict_types=1);

namespace App\Common\Doctrine\Uuid;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Symfony\Component\Uid\Uuid;

class UuidGenerator extends AbstractIdGenerator
{
    public function generate(EntityManager $em, $entity)
    {
        return Uuid::v6()->toRfc4122();
    }
}
