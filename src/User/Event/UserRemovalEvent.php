<?php

declare(strict_types=1);

namespace App\User\Event;

use App\User\Entity\User;

class UserRemovalEvent
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
