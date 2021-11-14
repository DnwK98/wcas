<?php

declare(strict_types=1);

namespace App\Website\Api;

use App\Common\Api\ApiController;
use App\Common\EitherResponseOrObject;
use App\Common\Form\FormValidator;
use App\Common\JsonObject\JsonObject;
use App\Common\Response\BadRequestResponse;
use App\Common\Response\CreatedResponse;
use App\Common\Response\ForbiddenResponse;
use App\Common\Response\NotFoundResponse;
use App\Common\Response\ObjectResponse;
use App\Common\Response\OkResponse;
use App\Page\PageFactory;
use App\User\UserPermissionService;
use App\Website\Api\Dto\WebsitePageDetailsDto;
use App\Website\Api\Dto\WebsitePageDto;
use App\Website\Api\Form\Status\StatusForm;
use App\Website\Api\Form\Status\StatusRequest;
use App\Website\Api\Form\WebsitePage\WebsitePageForm;
use App\Website\Api\Form\WebsitePage\WebsitePageRequest;
use App\Website\Entity\Repository\WebsiteRepository;
use App\Website\Entity\Website;
use App\Website\Entity\WebsitePage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WebsitePageController extends ApiController
{
    private UserPermissionService $userPermission;
    private WebsiteRepository $websiteRepository;
    private PageFactory $pageFactory;

    public function __construct(UserPermissionService $userPermission,
                                WebsiteRepository $websiteRepository,
                                PageFactory $pageFactory)
    {
        $this->userPermission = $userPermission;
        $this->websiteRepository = $websiteRepository;
        $this->pageFactory = $pageFactory;
    }

    /**
     * @Route("/api/website/{websiteId}/page/{pageId}", methods={"GET"})
     *
     * @param string $websiteId
     * @param string $pageId
     *
     * @return JsonResponse
     */
    public function getPage(string $websiteId, string $pageId): JsonResponse
    {
        $eitherWebsite = $this->getWebsite($websiteId);
        if ($eitherWebsite->response) {
            return $eitherWebsite->response;
        }
        /** @var Website $website */
        $website = $eitherWebsite->object;

        $page = $website->getPageById($pageId);
        if (!$page) {
            return new NotFoundResponse();
        }

        return new ObjectResponse(WebsitePageDetailsDto::fromEntity($page));
    }

    /**
     * @Route("/api/website/{websiteId}/page", methods={"GET"})
     *
     * @param string $websiteId
     *
     * @return JsonResponse
     */
    public function getList(string $websiteId): JsonResponse
    {
        $eitherWebsite = $this->getWebsite($websiteId);
        if ($eitherWebsite->response) {
            return $eitherWebsite->response;
        }
        /** @var Website $website */
        $website = $eitherWebsite->object;

        return new ObjectResponse($website->getPages()
            ->map(function (WebsitePage $page) {
                return WebsitePageDto::fromEntity($page);
            })
            ->toArray()
        );
    }

    /**
     * @Route("/api/website/{websiteId}/page", methods={"POST"})
     *
     * @param Request $request
     * @param string $websiteId
     *
     * @return JsonResponse
     */
    public function addPage(Request $request, string $websiteId): JsonResponse
    {
        $eitherWebsiteResponse = $this->getWebsite($websiteId);
        if ($eitherWebsiteResponse->response) {
            return $eitherWebsiteResponse->response;
        }
        $website = $eitherWebsiteResponse->getObject();

        $eitherDataResponse = $this->getFormData($request);
        if ($eitherDataResponse->response) {
            return $eitherDataResponse->response;
        }
        $data = $eitherDataResponse->getObject();

        $page = new WebsitePage();
        $this->modifyPage($page, $data);

        if ($website->hasPage($page)) {
            return new BadRequestResponse(['path' => 'Page with this path already exists']);
        }

        $website->addPage($page);

        $this->websiteRepository->save($website);

        return new CreatedResponse($page->getId());
    }

    /**
     * @Route("/api/website/{websiteId}/page/{pageId}", methods={"POST"})
     *
     * @param Request $request
     * @param string $websiteId
     * @param string $pageId
     *
     * @return JsonResponse
     */
    public function editPage(Request $request, string $websiteId, string $pageId): JsonResponse
    {
        $eitherWebsiteResponse = $this->getWebsite($websiteId);
        if ($eitherWebsiteResponse->response) {
            return $eitherWebsiteResponse->response;
        }
        /** @var Website $website */
        $website = $eitherWebsiteResponse->getObject();

        $eitherDataResponse = $this->getFormData($request);
        if ($eitherDataResponse->response) {
            return $eitherDataResponse->response;
        }
        /** @var WebsitePageRequest $data */
        $data = $eitherDataResponse->getObject();

        $page = $website->getPageById($pageId);
        if (!$page) {
            return new NotFoundResponse();
        }

        $this->modifyPage($page, $data);
        $this->websiteRepository->save($website);

        return new OkResponse();
    }

    /**
     * @Route("/api/website/{websiteId}/page/{pageId}/status", methods={"POST"})
     *
     * @param Request $request
     * @param string $websiteId
     * @param string $pageId
     *
     * @return JsonResponse
     */
    public function setPageStatus(Request $request, string $websiteId, string $pageId): JsonResponse
    {
        $eitherWebsiteResponse = $this->getWebsite($websiteId);
        if ($eitherWebsiteResponse->response) {
            return $eitherWebsiteResponse->response;
        }
        /** @var Website $website */
        $website = $eitherWebsiteResponse->getObject();

        $form = $this->createForm(StatusForm::class);
        $form->handleRequest($request);
        if ($errors = FormValidator::validate($form)) {
            return new BadRequestResponse($errors);
        }
        /** @var StatusRequest $data */
        $data = $form->getData();

        $page = $website->getPageById($pageId);
        if (!$page) {
            return new NotFoundResponse();
        }

        $page->setStatus($data->status);
        $this->websiteRepository->save($website);

        return new OkResponse();
    }

    /**
     * @Route("/api/website/{websiteId}/page/{pageId}", methods={"DELETE"})
     *
     * @param Request $request
     * @param string $websiteId
     * @param string $pageId
     *
     * @return JsonResponse
     */
    public function deletePage(Request $request, string $websiteId, string $pageId): JsonResponse
    {
        $eitherWebsiteResponse = $this->getWebsite($websiteId);
        if ($eitherWebsiteResponse->response) {
            return $eitherWebsiteResponse->response;
        }
        /** @var Website $website */
        $website = $eitherWebsiteResponse->getObject();

        $page = $website->getPageById($pageId);
        if (!$page) {
            return new NotFoundResponse();
        }

        $website->removePage($page);
        $this->websiteRepository->save($website);

        return new OkResponse();
    }

    /**
     * @param Request $request
     *
     * @return EitherResponseOrObject<WebsitePageRequest>
     */
    private function getFormData(Request $request): EitherResponseOrObject
    {
        $form = $this->createForm(WebsitePageForm::class);
        $form->handleRequest($request);
        if ($errors = FormValidator::validate($form)) {
            return EitherResponseOrObject::response(new BadRequestResponse($errors));
        }

        return EitherResponseOrObject::object($form->getData());
    }

    /**
     * @param string $websiteId
     *
     * @return EitherResponseOrObject<Website>
     */
    private function getWebsite(string $websiteId): EitherResponseOrObject
    {
        $user = $this->getUser();
        $website = $this->websiteRepository->find($websiteId);
        if (!$website) {
            return EitherResponseOrObject::response(new NotFoundResponse());
        }
        if (!$this->userPermission->hasAccessToObject($user, $website->getOwner())) {
            return EitherResponseOrObject::response(new ForbiddenResponse());
        }

        return EitherResponseOrObject::object($website);
    }

    private function modifyPage(WebsitePage $page, WebsitePageRequest $data)
    {
        $page->setPath($data->path);
        $page->setDefinition(
            $this->pageFactory
                ->build(JsonObject::ofJson($data->definition))
                ->toArray()
        );
    }
}
