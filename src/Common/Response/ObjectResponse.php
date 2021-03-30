<?php

namespace App\Common\Response;

use JsonSerializable;
use Symfony\Component\HttpFoundation\JsonResponse;

class ObjectResponse extends JsonResponse
{
    /**
     * @param JsonSerializable|array $data
     */
    public function __construct($data)
    {
        parent::__construct([
            'status' => 200,
            'data' => $data
        ]);
    }

}