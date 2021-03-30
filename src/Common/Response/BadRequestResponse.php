<?php

namespace App\Common\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class BadRequestResponse extends JsonResponse
{

    const STATUS = 400;

    public function __construct(array $errors)
    {
        parent::__construct([
            'status' => self::STATUS,
            'message' => 'Your request is invalid.',
            'errors' => $errors
        ], self::HTTP_BAD_REQUEST);
    }

}