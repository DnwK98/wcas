<?php

declare(strict_types=1);

namespace App\Common\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class CreatedResponse extends JsonResponse
{
    const STATUS = 201;

    public function __construct(string $id)
    {
        parent::__construct([
            'status' => self::STATUS,
            'id' => $id,
        ]);
    }
}
