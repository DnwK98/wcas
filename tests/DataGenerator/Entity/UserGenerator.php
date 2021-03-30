<?php

namespace App\Tests\DataGenerator\Entity;

use App\Tests\DataGenerator\DataGenerator;
use App\Tests\DataGenerator\Entity\AbstractEntityGenerator;
use App\User\Entity\User;
use App\User\UserRoles;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @property User $entity
 * @method User get()
 */
class UserGenerator extends AbstractEntityGenerator
{

    public function __construct(DataGenerator $dataGenerator, EntityManagerInterface $em)
    {
        parent::__construct($dataGenerator, $em);
        $this->entity = new User();
        $this->withEmail('test@example.com');
        $this->withTestPassword();
    }

    public function withEmail(string $email): UserGenerator
    {
        $this->entity->setEmail($email);
        return $this;
    }

    public function withTestPassword(): UserGenerator
    {
        // password: "password"
        $this->entity->setPassword('$2y$13$CmtH6dJP5GV0qOKf2sA.meIc.B/FETUlj4Mvmn1fS1A/vutoGKTEa');
        return $this;
    }

    public function administrator(): UserGenerator
    {
        $this->entity->setRoles([UserRoles::ADMINISTRATOR]);
        return $this;
    }
}