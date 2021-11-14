<?php

declare(strict_types=1);

namespace App\Page\Api;

use App\Common\JsonObject\Exception\JsonParseException;
use App\Common\JsonObject\JsonObject;
use App\Common\Response\BadRequestResponse;
use App\Page\PageFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagePreviewController extends AbstractController
{
    private PageFactory $pageFactory;

    public function __construct(PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
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
            return new BadRequestResponse([
                'body' => 'Is not valid JSON',
            ]);
        }

        $page = $this->pageFactory->build($pageJson);

        return new Response($page->render());
    }
}
