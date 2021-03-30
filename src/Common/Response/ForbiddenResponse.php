<?php

declare(strict_types=1);

namespace App\Common\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ForbiddenResponse extends JsonResponse
{
    public function __construct()
    {
        parent::__construct([
            'status' => 403,
            'message' => 'You don\'t have permission to visit this site',
        ], self::HTTP_FORBIDDEN);
    }
}
