<?php

declare(strict_types=1);

namespace App\Common\Url;

class Url
{
    /** @var string */
    private $protocol = '';

    /** @var string */
    private $domain;

    /** @var string */
    private $path;

    /** @var string */
    private $query;

    public static function fromString(string $url): self
    {
        $urlObject = new self();

        $protocolAndRest = explode('://', trim($url));
        if (count($protocolAndRest) > 1) {
            $urlObject->protocol = array_shift($protocolAndRest);
        }
        $urlWithoutProtocol = implode('://', $protocolAndRest);

        $parts = explode('?', $urlWithoutProtocol);
        $domainWithPath = array_shift($parts);
        $urlObject->query = implode('?', $parts);

        $parts = explode('/', $domainWithPath);
        $urlObject->domain = array_shift($parts);
        array_unshift($parts, '');

        $urlObject->path = implode('/', $parts);

        return $urlObject;
    }

    public function getProtocol(): string
    {
        return $this->protocol;
    }

    public function setProtocol(string $protocol): self
    {
        $this->protocol = $protocol;

        return $this;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getQueryParameter(string $key): ?string
    {
        parse_str($this->query, $result);
        $response = $result[$key] ?? null;

        return is_string($response) ? $response : null;
    }

    public function setQuery(string $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function getUrl(): string
    {
        return implode('', [
            ('' !== $this->protocol) ? $this->protocol . '://' : '',
            $this->domain,
            $this->path,
            ('' !== $this->query) ? '?' . $this->query : '',
        ]);
    }

    public function getDomainWithProtocol(): string
    {
        return implode('', [
            ('' !== $this->protocol) ? $this->protocol . '://' : '',
            $this->domain,
        ]);
    }

    public function getPathWithQuery(): string
    {
        return implode('', [
            $this->path,
            ('' !== $this->query) ? '?' . $this->query : '',
        ]);
    }
}
