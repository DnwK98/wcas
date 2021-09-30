<?php

declare(strict_types=1);

namespace App\Serve\Controller;

use App\Common\Response\NotFoundResponse;
use App\Serve\ServeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ServeController extends AbstractController
{
    private ServeService $service;

    public function __construct(ServeService $service)
    {
        $this->service = $service;
    }

    public function main(Request $request)
    {
        $url = $request->getHost() .  $request->getPathInfo();
        $content = $this->service->getUrlContent($url);

        if (null === $content) {
            return new NotFoundResponse();
        }

        return new Response($content);
    }
}
