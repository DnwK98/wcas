<?php

declare(strict_types=1);

namespace App\Website\Api\Form;

use Symfony\Component\Validator\Constraints as Assert;

class WebsiteRequest
{
    /**
     * @var string
     * @Assert\Length(min=5)
     * @Assert\NotBlank
     */
    public string $url;

    /**
     * @return bool
     * @Assert\IsTrue
     */
    public function isValidUrl(): bool
    {
        return (bool)preg_match('%^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{0,10}$%i', $this->url);
    }
}
