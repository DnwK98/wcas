<?php

declare(strict_types=1);

namespace App\Page\Component;

use App\Common\Response\BadRequestResponse;
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
        return new BadRequestResponse([]);
    }

    public function toArray()
    {
        return $this->jsonSerialize();
    }

    protected function outputGet(callable $function): string
    {
        ob_start();
        $function();

        return (string)ob_get_clean();
    }
}
