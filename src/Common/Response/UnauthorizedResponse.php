<?php

declare(strict_types=1);

namespace App\Common\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class UnauthorizedResponse extends JsonResponse
{
    public function __construct(array $errors)
    {
        parent::__construct([
            'status' => 401,
            'errors' => $errors,
        ], self::HTTP_UNAUTHORIZED);
    }
}
