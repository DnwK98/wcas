<?php

namespace App\Page\Component;

use JsonSerializable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractComponent implements JsonSerializable
{
    public function render(): string
    {
        return '';
    }

    public function handleRequest(Request $request): JsonResponse
    {
        return new JsonResponse(['status' => 'Bad Request'], JsonResponse::HTTP_BAD_REQUEST);
    }

    protected function outputGet(callable $function): string
    {
        ob_start();
        $function();
        return (string)ob_get_clean();
    }
}