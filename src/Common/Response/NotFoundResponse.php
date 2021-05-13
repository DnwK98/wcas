<?php

declare(strict_types=1);

namespace App\Common\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class NotFoundResponse extends JsonResponse
{
    const STATUS = 404;

    public function __construct()
    {
        parent::__construct([
            'status' => self::STATUS,
            'message' => 'Resource not found',
        ], self::HTTP_NOT_FOUND);
    }
}
