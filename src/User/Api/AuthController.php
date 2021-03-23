<?php

namespace App\User\Api;

use App\Common\Form\FormValidator;
use App\User\Api\Form\Login\LoginForm;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use App\User\Api\Form\Register\RegisterForm;
use App\User\Api\Form\Register\RegisterRequest;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthController extends AbstractController
{
    private UserRepository $userRepository;
    private JWTTokenManagerInterface $jwtManager;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(EntityManagerInterface $em,
                                JWTTokenManagerInterface $jwtManager,
                                UserPasswordEncoderInterface $passwordEncoder)
    {

        /** @var UserRepository $repository */
        $repository = $em->getRepository(User::class);
        $this->userRepository = $repository;
        $this->jwtManager = $jwtManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/api/auth/login", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $form = $this->createForm(LoginForm::class);
        $form->handleRequest($request);
        if($errors = FormValidator::validate($form)) {
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }
        /** @var RegisterRequest $data */
        $data = $form->getData();

        if(!$user = $this->userRepository->findOneByEmail($data->email)){
            return new JsonResponse(['error' => 'Invalid email'], JsonResponse::HTTP_UNAUTHORIZED);
        }
        if(!$this->passwordEncoder->isPasswordValid($user, $data->password)){
            return new JsonResponse(['error' => 'Invalid password'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'token' => $this->jwtManager->create($user)
        ]);
    }

    /**
     * @Route("/api/auth/register", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $form = $this->createForm(RegisterForm::class);
        $form->handleRequest($request);
        if($errors = FormValidator::validate($form)) {
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        /** @var RegisterRequest $data */
        $data = $form->getData();

        if($this->userRepository->findOneByEmail($data->email)){
            return new JsonResponse(['errors' => ['email' => 'Email already exists']], JsonResponse::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setEmail($data->email);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $data->password));

        $this->userRepository->save($user);

        return new JsonResponse([
            'status' => 'success',
            'userId' => $user->getId()
        ]);
    }

    /**
     * @Route("/api/auth/refresh", methods={"POST"})
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        $user = $this->getUser();
        if(!$user){
            return new JsonResponse(['error' => 'Invalid or expired JWT'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'token' => $this->jwtManager->create($user)
        ]);
    }
}