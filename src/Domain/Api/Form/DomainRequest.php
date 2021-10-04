<?php

declare(strict_types=1);

namespace App\Domain\Api\Form;

use Symfony\Component\Validator\Constraints as Assert;

class DomainRequest
{
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(min=5)
     * @Assert\Expression("this.isDomainValid()", message="Domain format is not valid")
     */
    public string $domain = '';

    public function isDomainValid(): bool
    {
        return (bool)preg_match('%^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{0,10}$%i', $this->domain);
    }
}
