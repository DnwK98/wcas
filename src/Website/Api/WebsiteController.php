<?php

declare(strict_types=1);

namespace App\Website\Api;

use App\Common\Api\ApiController;
use App\Common\Doctrine\Collection\Collection;
use App\Common\Form\FormValidator;
use App\Common\Response\BadRequestResponse;
use App\Common\Response\CreatedResponse;
use App\Common\Response\ForbiddenResponse;
use App\Common\Response\NotFoundResponse;
use App\Common\Response\ObjectResponse;
use App\Common\Response\OkResponse;
use App\Domain\DomainValidator;
use App\User\UserPermissionService;
use App\Website\Api\Dto\WebsiteDetailsDto;
use App\Website\Api\Dto\WebsiteDto;
use App\Website\Api\Form\Status\StatusForm;
use App\Website\Api\Form\Status\StatusRequest;
use App\Website\Api\Form\Website\WebsiteForm;
use App\Website\Api\Form\Website\WebsiteRequest;
use App\Website\Entity\Repository\WebsiteRepository;
use App\Website\Entity\Website;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WebsiteController extends ApiController
{
    private UserPermissionService $userPermission;
    private DomainValidator $domainValidator;
    private WebsiteRepository $websiteRepository;

    public function __construct(UserPermissionService $userPermission,
                                WebsiteRepository $websiteRepository,
                                DomainValidator $domainValidator)
    {
        $this->userPermission = $userPermission;
        $this->domainValidator = $domainValidator;
        $this->websiteRepository = $websiteRepository;
    }

    /**
     * @Route("/api/website", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getList(): JsonResponse
    {
        $websites = Collection::Collect($this->websiteRepository->findForUser($this->getUser()));

        return new ObjectResponse($websites
            ->map(function (Website $website) {
                return WebsiteDto::fromEntity($website);
            })
            ->toArray()
        );
    }

    /**
     * @Route("/api/website/{id}", methods={"GET"})
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function getDetails(string $id): JsonResponse
    {
        $website = $this->websiteRepository->find($id);
        if (!$website) {
            return new NotFoundResponse();
        }

        if (!$this->userPermission->hasAccessToObject($this->getUser(), $website->getOwner())) {
            return new ForbiddenResponse();
        }

        return new ObjectResponse(WebsiteDetailsDto::fromEntity($website));
    }

    /**
     * @Route("/api/website", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addWebsite(Request $request): JsonResponse
    {
        $form = $this->createForm(WebsiteForm::class);
        $form->handleRequest($request);
        if ($errors = FormValidator::validate($form)) {
            return new BadRequestResponse($errors);
        }
        /** @var WebsiteRequest $data */
        $data = $form->getData();

        if (!$this->domainValidator->hasValidDomainForUser($data->url, $this->getUser())) {
            return new BadRequestResponse(['url' => 'Url does not contain valid domain']);
        }
        if ($this->websiteRepository->findByUrl($data->url)) {
            return new BadRequestResponse(['url' => 'Website for this url already exists']);
        }

        $website = new Website();
        $website->setUrl($data->url);
        $website->setOwner($this->getUser());
        $this->websiteRepository->save($website);

        return new CreatedResponse($website->getId());
    }

    /**
     * @Route("/api/website/{id}", methods={"GET"})
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function deleteWebsite(string $id): JsonResponse
    {
        $website = $this->websiteRepository->find($id);
        if (!$website) {
            return new NotFoundResponse();
        }

        if (!$this->userPermission->hasAccessToObject($this->getUser(), $website->getOwner())) {
            return new ForbiddenResponse();
        }

        $this->websiteRepository->delete($website);

        return new OkResponse();
    }

    /**
     * @Route("/api/website/{id}/status", methods={"GET"})
     *
     * @param Request $request
     * @param string $id
     *
     * @return JsonResponse
     */
    public function setWebsiteStatus(Request $request, string $id): JsonResponse
    {
        $form = $this->createForm(StatusForm::class);
        $form->handleRequest($request);
        if ($errors = FormValidator::validate($form)) {
            return new BadRequestResponse($errors);
        }
        /** @var StatusRequest $data */
        $data = $form->getData();

        $website = $this->websiteRepository->find($id);
        if (!$website) {
            return new NotFoundResponse();
        }

        if (!$this->userPermission->hasAccessToObject($this->getUser(), $website->getOwner())) {
            return new ForbiddenResponse();
        }

        $website->setStatus($data->status);
        $this->websiteRepository->save($website);

        return new OkResponse();
    }
}
