<?php

declare(strict_types=1);

namespace App\Website\Api\Form\WebsitePage;

use Symfony\Component\Validator\Constraints as Assert;

class WebsitePageRequest
{
    /**
     * @var ?string
     * @Assert\Length(min=3)
     * @Assert\NotBlank
     * @Assert\Expression("this.isPathValid()", message="Path has invalid format")
     */
    public ?string $path = null;

    /**
     * @var ?string
     * @Assert\NotBlank
     * @Assert\Expression("this.isValidJson()", message="Invalid json value")
     */
    public ?string $definition = null;

    public function isValidJson(): bool
    {
        json_decode($this->definition ?? '');

        return 0 === json_last_error();
    }

    public function isPathValid(): bool
    {
        $parts = explode('/', $this->path ?? '');
        foreach ($parts as $part) {
            if (empty($part)) {
                return false;
            }
        }

        return true;
    }
}
