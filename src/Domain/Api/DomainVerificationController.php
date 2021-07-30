<?php

declare(strict_types=1);

namespace App\Domain\Api;

use App\Common\Response\ObjectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DomainVerificationController
{
    /**
     * @Route("/api/domain/verification/{hash}", methods={"GET"})
     *
     * @param string $hash
     *
     * @return JsonResponse
     */
    public function verification(string $hash): JsonResponse
    {
        // TODO crypto verification
        return new ObjectResponse([
            'verification' => $hash,
        ]);
    }
}
