<?php

declare(strict_types=1);

namespace App\Page\Component\YouTube;

use App\Common\Url\Url;
use App\Page\Component\AbstractComponent;

class YouTubeComponent extends AbstractComponent
{
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function getEmbedUrl(): string
    {
        $url = Url::fromString($this->url);

        if (strpos($url->getPath(), '/embed/')) {
            return $url->getUrl();
        }

        return Url::fromString('https://youtube.com')
            ->setPath('/embed/' . $url->getQueryParameter('v'))
            ->getUrl();
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => 'YouTubeComponent',
            'url' => $this->url,
        ];
    }

    public function render(): string
    {
        return $this->outputGet(function () {
            require __DIR__ . '/view.phtml';
        });
    }
}
