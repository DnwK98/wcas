<?php

namespace App\User\Api;

use App\User\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/api/me", methods={"GET"})
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getUsername(),
            'created' => date('Y-m-d H:i:s', $user->getUuid()->getTime())
        ]);
    }
}