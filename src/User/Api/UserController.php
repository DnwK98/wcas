<?php

declare(strict_types=1);

namespace App\User\Api;

use App\Common\Api\ApiController;
use App\Common\Form\FormValidator;
use App\Common\Response\BadRequestResponse;
use App\Common\Response\ForbiddenResponse;
use App\Common\Response\ObjectResponse;
use App\Common\Response\OkResponse;
use App\User\Api\Dto\UserDto;
use App\User\Api\Form\Password\PasswordForm;
use App\User\Api\Form\Password\PasswordRequest;
use App\User\Entity\Repository\UserRepository;
use App\User\Entity\User;
use App\User\Event\UserRemovalEvent;
use App\User\UserPermissionService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends ApiController
{
    private UserPermissionService $permissionService;
    private UserRepository $userRepository;
    private UserPasswordEncoderInterface $passwordEncoder;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(UserPermissionService $permissionService,
                                UserRepository $userRepository,
                                UserPasswordEncoderInterface $passwordEncoder,
                                EventDispatcherInterface $eventDispatcher)
    {
        $this->permissionService = $permissionService;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/api/me", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        $user = $this->getUser();

        return new ObjectResponse(UserDto::fromEntity($user));
    }

    /**
     * @Route("/api/me/password", methods={"POST"})
     *
     * @return JsonResponse
     */
    public function password(Request $request)
    {
        $form = $this->createForm(PasswordForm::class);
        $form->handleRequest($request);
        if ($errors = FormValidator::validate($form)) {
            return new BadRequestResponse($errors);
        }
        /** @var PasswordRequest $data */
        $data = $form->getData();

        $user = $this->getUser();

        if (!$this->passwordEncoder->isPasswordValid($user, $data->oldPassword)) {
            return new BadRequestResponse(['oldPassword' => 'Invalid password']);
        }

        $user->setPassword($this->passwordEncoder->encodePassword($user, $data->newPassword));

        return new OkResponse();
    }

    /**
     * @Route("/api/me", methods={"DELETE"})
     *
     * @return JsonResponse
     */
    public function delete()
    {
        $user = $this->getUser();

        $this->eventDispatcher->dispatch(new UserRemovalEvent($user));
        $this->userRepository->delete($user);

        return new OkResponse();
    }

    /**
     * @Route("/api/user", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function users()
    {
        $user = $this->getUser();
        if (!$this->permissionService->hasAccessToUsersList($user)) {
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
