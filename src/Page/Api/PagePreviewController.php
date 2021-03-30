<?php

declare(strict_types=1);

namespace App\Page\Api;

use App\Common\JsonObject\Exception\JsonParseException;
use App\Common\JsonObject\JsonObject;
use App\Page\PageBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagePreviewController extends AbstractController
{
    private PageBuilder $pageBuilder;

    public function __construct(PageBuilder $pageBuilder)
    {
        $this->pageBuilder = $pageBuilder;
    }

    /**
     * @Route("/api/page/preview", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function preview(Request $request): Response
    {
        $pageJson = null;
        try {
            $pageJson = JsonObject::ofJson($request->getContent());
        } catch (JsonParseException $e) {
            return new JsonResponse(['status' => 'Bad Request'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $page = $this->pageBuilder->build($pageJson);

        return new Response($page->render());
    }
}
