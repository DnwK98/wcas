<?php

declare(strict_types=1);

namespace App\User\Api\Form\Register;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterRequest
{
    /**
     * @var string
     * @Assert\Email
     * @Assert\NotBlank
     */
    public string $email;

    /**
     * @var string
     * @Assert\Length(min="8")
     * @Assert\NotBlank
     */
    public string $password;
}
