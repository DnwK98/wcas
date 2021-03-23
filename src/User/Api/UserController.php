<?php

namespace App\User\Api;

use App\Common\Response\ForbiddenResponse;
use App\User\Api\Dto\UserDto;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use App\User\UserPermissionService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserPermissionService $permissionService;
    private UserRepository $userRepository;

    public function __construct(UserPermissionService $permissionService, UserRepository $userRepository)
    {
        $this->permissionService = $permissionService;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/api/me", methods={"GET"})
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        return new JsonResponse(UserDto::fromEntity($user));
    }

    /**
     * @Route("/api/user", methods={"GET"})
     * @return JsonResponse
     */
    public function users()
    {
        /** @var User $user */
        $user = $this->getUser();
        if(!$this->permissionService->hasAccessToUsersList($user)){
            return new ForbiddenResponse();
        }

        $users = new ArrayCollection($this->userRepository->findAll());
        return new JsonResponse(
            $users
                ->map(function (User $user) {
                    return UserDto::fromEntity($user);
                })
                ->toArray()
        );
    }
}