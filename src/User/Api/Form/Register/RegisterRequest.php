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

    /**
     * @var string
     * @Assert\Length(min="8")
     * @Assert\NotBlank
     */
    public string $passwordVerify;

    /**
     * @Assert\IsTrue(message="Passwords must be equals", groups="passwordVerify")
     */
    public function isVerifiedPasswordEqualsToPassword()
    {
        return $this->password === $this->passwordVerify;
    }
}
