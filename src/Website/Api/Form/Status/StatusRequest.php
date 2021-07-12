<?php

declare(strict_types=1);

namespace App\Website\Api\Form\Status;

use Symfony\Component\Validator\Constraints as Assert;

class StatusRequest
{
    /**
     * @var string
     * @Assert\Length(min=3)
     * @Assert\NotBlank
     */
    public string $status;
}
