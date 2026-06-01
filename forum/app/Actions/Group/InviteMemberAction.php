<?php

namespace App\Actions\Group;

use App\Models\Group;
use App\Models\GroupInvitation;
use App\Models\User;
use App\Services\GroupService;

class InviteMemberAction
{
    public function __construct(
        private GroupService $groupService,
    ) {}

    public function execute(Group $group, User $inviter, string $email): GroupInvitation
    {
        return $this->groupService->invite($group, $inviter, $email);
    }
}
