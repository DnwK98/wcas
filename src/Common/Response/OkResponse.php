<?php

declare(strict_types=1);

namespace App\Common\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class OkResponse extends JsonResponse
{
    const STATUS = 200;

    public function __construct()
    {
        parent::__construct([
            'status' => self::STATUS,
        ]);
    }
}
