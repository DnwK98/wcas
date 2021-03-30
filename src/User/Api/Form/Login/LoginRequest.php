<?php

declare(strict_types=1);

namespace App\User\Api\Form\Login;

use Symfony\Component\Validator\Constraints as Assert;

class LoginRequest
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
