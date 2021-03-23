<?php

namespace App\Common\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ForbiddenResponse extends JsonResponse
{

    public function __construct()
    {
        parent::__construct([
            'status' => 403,
            'message' => 'You don\' have permission to visit this site'
        ], self::HTTP_FORBIDDEN);
    }

}