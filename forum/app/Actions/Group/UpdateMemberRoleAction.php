<?php

namespace App\Actions\Group;

use App\Models\Group;
use App\Models\User;
use App\Services\GroupService;

class UpdateMemberRoleAction
{
    public function __construct(
        private GroupService $groupService,
    ) {}

    public function execute(Group $group, int $targetUserId, string $role, User $actor): bool
    {
        return $this->groupService->updateMemberRole($group, $targetUserId, $role, $actor);
    }
}
