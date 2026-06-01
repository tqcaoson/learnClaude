<?php

namespace App\Actions\Group;

use App\DTOs\GroupDTO;
use App\Models\Group;
use App\Models\User;
use App\Services\GroupService;

class CreateGroupAction
{
    public function __construct(
        private GroupService $groupService,
    ) {}

    public function execute(User $user, GroupDTO $dto): Group
    {
        return $this->groupService->create($user, $dto);
    }
}
