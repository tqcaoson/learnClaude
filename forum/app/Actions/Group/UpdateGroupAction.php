<?php

namespace App\Actions\Group;

use App\DTOs\GroupDTO;
use App\Models\Group;
use App\Services\GroupService;

class UpdateGroupAction
{
    public function __construct(
        private GroupService $groupService,
    ) {}

    public function execute(Group $group, GroupDTO $dto): Group
    {
        return $this->groupService->update($group, $dto);
    }
}
