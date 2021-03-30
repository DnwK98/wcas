<?php

declare(strict_types=1);

namespace App\Common\Api;

use App\User\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ApiController extends AbstractController
{
    public function getUser(): User
    {
        $user = parent::getUser();

        if (!$user instanceof User) {
            throw new UnauthorizedHttpException('');
        }

        return $user;
    }
}
