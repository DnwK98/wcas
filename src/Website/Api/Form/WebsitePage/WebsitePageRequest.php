<?php

declare(strict_types=1);

namespace App\Website\Api\Form\WebsitePage;

use Symfony\Component\Validator\Constraints as Assert;

class WebsitePageRequest
{
    /**
     * @var ?string
     * @Assert\Length(min=5)
     * @Assert\NotBlank
     */
    public ?string $path = null;

    /**
     * @var ?string
     * @Assert\NotBlank
     */
    public ?string $definition = null;

    /**
     * @return bool
     * @Assert\IsTrue(message="Invalid json value", groups="definition")
     */
    public function isValidJson(): bool
    {
        json_decode($this->definition ?? '');

        return 0 === json_last_error();
    }
}
