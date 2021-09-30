<?php

declare(strict_types=1);

namespace App\Common\Log;

use Symfony\Component\HttpFoundation\RequestStack;

class SessionRequestProcessor
{
    private RequestStack $requestStack;
    private string $requestId;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->requestId = substr(md5(time() . rand(0, 999999)), 0, 8);
    }

    // this method is called for each log record; optimize it to not hurt performance
    public function __invoke(array $record)
    {
        $record['extra']['token'] = $this->getSessionId() . ':' . $this->requestId;

        return $record;
    }

    private function getSessionId()
    {
        $session = null;

        try {
            $session = $this->requestStack->getCurrentRequest()->getSession();
        } catch (\Throwable $e) {
        }

        if (null === $session) {
            return '';
        }

        if (!$session->isStarted()) {
            return '';
        }

        return substr($session->getId(), 0, 8);
    }
}
