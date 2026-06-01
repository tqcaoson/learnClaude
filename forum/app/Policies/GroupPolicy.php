<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPolicy
{
    public function view(User $user, Group $group): bool
    {
        return $group->isPublic() || $group->isMember($user) || $user->isAdmin();
    }

    public function update(User $user, Group $group): bool
    {
        return $group->isOwner($user) || $user->isAdmin();
    }

    public function delete(User $user, Group $group): bool
    {
        return $group->isOwner($user) || $user->isAdmin();
    }

    public function manageMember(User $user, Group $group): bool
    {
        $role = $group->getMemberRole($user);
        return in_array($role, ['owner', 'moderator']) || $user->isAdmin();
    }

    public function post(User $user, Group $group): bool
    {
        return $group->canPost($user);
    }
}
