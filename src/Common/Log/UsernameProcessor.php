<?php

declare(strict_types=1);

namespace App\Common\Log;

use App\User\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UsernameProcessor
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    // this method is called for each log record; optimize it to not hurt performance
    public function __invoke(array $record)
    {
        $user = $this->getUser();

        $record['extra']['username'] = $user ?? 'anon';

        return $record;
    }

    private function getUser()
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        if (null === $user = $token->getUser()) {
            return null;
        }

        if (!$user instanceof User) {
            return null;
        }

        return $user->getUsername();
    }
}
