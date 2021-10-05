<?php

declare(strict_types=1);

namespace App\Common\Log;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AccessLogEventSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $log;

    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        $this->log->info(sprintf('ACCESS | %s | %s | %s | %s',
            $request->getMethod(),
            $request->getUri(),
            $response->getStatusCode(),
            $request->headers->get('User-Agent', '-')
        ));
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
