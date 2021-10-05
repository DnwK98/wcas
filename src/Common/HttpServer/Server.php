<?php

declare(strict_types=1);

namespace App\Common\HttpServer;

use Amp\Cluster\Cluster;
use Amp\Http\Server\HttpServer;
use Amp\Http\Server\Options;
use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler\CallableRequestHandler;
use Amp\Http\Server\Response;
use Amp\Loop;
use App\Kernel;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Server
{
    private int $port = 80;
    private ?Kernel $kernel = null;
    private int $kernelRequests = 0;
    private int $requestsPerGC = 500;
    private array $serverGlobals = [];

    public static function Create(array $server = []): self
    {
        return new self($server);
    }

    public function __construct(array $server)
    {
        $this->serverGlobals = $server;
    }

    public function setPort(int $port): self
    {
        $this->port = $port;

        return $this;
    }

    public function setRequestsPerGC(int $requestsPerGC): Server
    {
        $this->requestsPerGC = $requestsPerGC;

        return $this;
    }

    public function run()
    {
        if (!gc_enabled()) {
            gc_enable();
        }

        Loop::run(function () {
            $sockets = yield [
                Cluster::listen('0.0.0.0:' . $this->port),
                Cluster::listen('[::]:' . $this->port),
            ];

            $options = new Options();
            $options = $options->withBodySizeLimit(254 * 1024 * 1024);

            /** @psalm-suppress InvalidArgument // yield resolves promise */
            $server = new HttpServer(
                $sockets,
                new CallableRequestHandler(function (Request $request) {
                    if (!$this->kernel) {
                        $this->kernel = $this->buildKernel();
                    }
                    ++$this->kernelRequests;

                    $content = yield $request->getBody()->buffer();
                    $response = $this->handleRequest($request, (string)$content);
                    $this->garbageCollect();

                    return $response;
                }),
                new NullLogger(),
                $options
            );

            yield $server->start();

            Loop::onSignal(SIGINT, function (string $watcherId) use ($server) {
                Loop::cancel($watcherId);
                yield $server->stop();
            });
        });
    }

    private function handleRequest(Request $request, string $content): Response
    {
        try {
            $method = $request->getMethod();
            switch (strtoupper($method)) {
                case 'POST':
                case 'PUT':
                case 'DELETE':
                case 'PATCH':
                    parse_str($content, $params);
                    break;
                default:
                    parse_str($request->getUri()->getQuery(), $params);
                    break;
            }

            $symfonyRequest = SymfonyRequest::create(
                $request->getUri()->getPath(),
                $request->getMethod(),
                $params,
                $request->getCookies(),
                [],
                $this->serverGlobals,
                $content
            );

            foreach ($request->getHeaders() as $name => $value) {
                $symfonyRequest->headers->set($name, $value);
            }

            $symfonyResponse = $this->kernel->handle($symfonyRequest);

            return new Response(
                $symfonyResponse->getStatusCode(),
                $symfonyResponse->headers->all(),
                $symfonyResponse->getContent()
            );
        } catch (\Throwable $e) {
            return new Response(500, [], implode(PHP_EOL . PHP_EOL, [
                'Internal server error',
                $this->serverGlobals['APP_DEBUG'] ? $e->getMessage() : '',
                $this->serverGlobals['APP_DEBUG'] ? $e->getTraceAsString() : '',
            ]));
        }
    }

    private function buildKernel(): Kernel
    {
        return new Kernel($this->serverGlobals['APP_ENV'], (bool)$this->serverGlobals['APP_DEBUG']);
    }

    private function garbageCollect()
    {
        if ($this->kernelRequests > $this->requestsPerGC) {
            $this->kernel->shutdown();
            $this->kernel = null;
            $this->kernelRequests = 0;

            gc_collect_cycles();
        }
    }
}
