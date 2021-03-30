<?php

declare(strict_types=1);

namespace App\User\Api\Dto;

use App\User\Entity\User;
use JsonSerializable;

class UserDto implements JsonSerializable
{
    public string $id;
    public string $email;
    public string $created;

    public static function fromEntity(User $user): UserDto
    {
        $dto = new self();
        $dto->id = (string)$user->getId();
        $dto->email = (string)$user->getEmail();
        $dto->created = date('Y-m-d H:i:s', (int)$user->getUuid()->getTime());

        return $dto;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
