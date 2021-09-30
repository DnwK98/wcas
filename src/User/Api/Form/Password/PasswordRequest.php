<?php

declare(strict_types=1);

namespace App\User\Api\Form\Password;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordRequest
{
    /**
     * @var ?string
     * @Assert\Length(min="8")
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    public ?string $oldPassword = '';

    /**
     * @var ?string
     * @Assert\Length(min="8")
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    public ?string $newPassword = '';

    /**
     * @var ?string
     * @Assert\Length(min="8")
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Expression("this.isVerifiedPasswordEqualsToPassword()", message="Passwords must be equal")
     */
    public ?string $newPasswordVerify = '';

    public function isVerifiedPasswordEqualsToPassword(): bool
    {
        return $this->newPassword === $this->newPasswordVerify;
    }
}
