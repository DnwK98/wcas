<?php


namespace App\Tests\TestClass;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class TestRequest
{
    private KernelBrowser $client;
    private string $uri;
    private string $method= 'GET';
    private array $parameters = [];
    private array $server = [];
    private ?string $content = null;

    public function __construct(KernelBrowser $client)
    {
        $this->client = $client;
    }

    public function uri(string $uri): self
    {
        $this->uri = $uri;
        return $this;
    }

    public function method(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    public function parameters(array $parameters): self
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function addHeader(string $name, string $value): TestRequest
    {
        $this->server['HTTP_' . strtoupper($name)] = $value;
        return $this;
    }

    public function json(array $jsonArray): TestRequest
    {
        $this->addHeader('CONTENT_TYPE', 'application/json');
        $this->content = json_encode($jsonArray);
        return $this;
    }

    public function getResponse(): string
    {
        $this->client->request($this->method, $this->uri, $this->parameters, [], $this->server, $this->content);
        return (string)$this->client->getResponse()->getContent();
    }
}