<?php

declare(strict_types=1);

namespace App\Website\Api;

use App\Common\Api\ApiController;
use App\Common\Form\FormValidator;
use App\Common\Response\BadRequestResponse;
use App\Common\Response\CreatedResponse;
use App\Common\Response\ObjectResponse;
use App\Domain\DomainValidator;
use App\User\UserPermissionService;
use App\Website\Api\Form\WebsiteForm;
use App\Website\Api\Form\WebsiteRequest;
use App\Website\Entity\Repository\WebsiteRepository;
use App\Website\Entity\Website;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WebsiteManagementController extends ApiController
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

    public function getList(): JsonResponse
    {
        return new ObjectResponse([]);
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

        $website = new Website();
        $website->setUrl($data->url);
        $website->setOwner($this->getUser());
        $this->websiteRepository->save($website);

        return new CreatedResponse($website->getId());
    }
}