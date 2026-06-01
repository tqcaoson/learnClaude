<?php

namespace App\Actions\Group;

use App\Models\Group;
use App\Models\User;
use App\Services\GroupService;

class KickMemberAction
{
    public function __construct(
        private GroupService $groupService,
    ) {}

    public function execute(Group $group, int $targetUserId, User $actor): bool
    {
        return $this->groupService->kickMember($group, $targetUserId, $actor);
    }
}
