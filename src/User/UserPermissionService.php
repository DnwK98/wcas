<?php

namespace App\User;

use App\User\Entity\User;

class UserPermissionService
{
    public function hasAccessToUsersList(User $user): bool
    {
        return $this->userHasRole($user, [
            UserRoles::ADMINISTRATOR
        ]);
    }

    private function userHasRole(User $user, array $roles): bool
    {
        foreach ($user->getRoles() as $userRole){
            if(in_array($userRole, $roles)){
                return true;
            }
        }

        return false;
    }
}