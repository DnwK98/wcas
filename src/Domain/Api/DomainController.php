<?php

declare(strict_types=1);

namespace App\Domain\Api;

use App\Common\Api\ApiController;
use App\Common\Doctrine\Collection\Collection;
use App\Common\Form\FormValidator;
use App\Common\Response\BadRequestResponse;
use App\Common\Response\CreatedResponse;
use App\Common\Response\ForbiddenResponse;
use App\Common\Response\NotFoundResponse;
use App\Common\Response\ObjectResponse;
use App\Domain\Api\Dto\DomainDto;
use App\Domain\Api\Dto\DomainVerificationDto;
use App\Domain\Api\Form\DomainForm;
use App\Domain\Api\Form\DomainRequest;
use App\Domain\DomainValidator;
use App\Domain\Entity\Domain;
use App\Domain\Entity\Repository\DomainRepository;
use App\User\UserPermissionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DomainController extends ApiController
{
    private UserPermissionService $userPermission;
    private DomainValidator $domainValidator;
    private DomainRepository $domainRepository;

    public function __construct(UserPermissionService $userPermission,
                                DomainValidator $domainValidator,
                                DomainRepository $domainRepository)
    {
        $this->userPermission = $userPermission;
        $this->domainValidator = $domainValidator;
        $this->domainRepository = $domainRepository;
    }

    /**
     * @Route("/api/domain", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function domainList(): JsonResponse
    {
        $domains = Collection::Collect($this->domainRepository->findForUser($this->getUser()));

        return new ObjectResponse($domains
            ->map(function (Domain $domain) {
                return DomainDto::fromEntity($domain);
            })
            ->toArray()
        );
    }

    /**
     * @Route("/api/domain", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addDomain(Request $request): JsonResponse
    {
        $form = $this->createForm(DomainForm::class);
        $form->handleRequest($request);
        if ($errors = FormValidator::validate($form)) {
            return new BadRequestResponse($errors);
        }
        /** @var DomainRequest $data */
        $data = $form->getData();

        if (!$this->domainValidator->domainDoesNotOverlapsExistingOne($data->domain)) {
            return new BadRequestResponse(['domain' => 'This domain is added already']);
        }

        $domain = new Domain();
        $domain->setDomain($data->domain);
        $domain->setOwner($this->getUser());

        $this->domainRepository->save($domain);

        return new CreatedResponse($domain->getId());
    }

    /**
     * @Route("/api/domain/{id}/verification", methods={"GET"})
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function domainVerification(string $id): JsonResponse
    {
        $domain = $this->domainRepository->find($id);
        if (null === $domain) {
            return new NotFoundResponse();
        }
        if (!$this->userPermission->hasAccessToObject($this->getUser(), $domain->getOwner())) {
            return new ForbiddenResponse();
        }

        return new ObjectResponse(DomainVerificationDto::Create($domain, ['192.168.0.1']));
    }
}
