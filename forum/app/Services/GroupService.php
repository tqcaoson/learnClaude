<?php

namespace App\Services;

use App\DTOs\GroupDTO;
use App\Models\Group;
use App\Models\GroupInvitation;
use App\Models\GroupMember;
use App\Models\User;
use App\Repositories\GroupRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class GroupService
{
    public function __construct(
        private GroupRepository $groupRepository,
    ) {}

    public function create(User $owner, GroupDTO $dto): Group
    {
        $slug = $this->generateUniqueSlug($dto->name);

        $group = $this->groupRepository->create([
            'owner_id' => $owner->id,
            'name' => $dto->name,
            'slug' => $slug,
            'description' => $dto->description ?? '',
            'type' => $dto->type,
            'avatar' => $dto->avatarPath,
            'members_count' => 1,
        ]);

        $this->groupRepository->addMember($group->id, $owner->id, 'owner');

        return $group;
    }

    public function update(Group $group, GroupDTO $dto): Group
    {
        $data = [
            'name' => $dto->name,
            'description' => $dto->description ?? '',
            'type' => $dto->type,
        ];

        if ($dto->avatarPath !== null) {
            $data['avatar'] = $dto->avatarPath;
        }

        $group->update($data);

        return $group->fresh();
    }

    public function delete(Group $group): bool
    {
        return $group->delete();
    }

    public function join(Group $group, User $user): GroupMember
    {
        if ($group->isPrivate()) {
            throw new RuntimeException('This is a private group. You need an invitation to join.');
        }

        if ($group->isMember($user)) {
            throw new RuntimeException('You are already a member of this group.');
        }

        $member = $this->groupRepository->addMember($group->id, $user->id, 'member');
        $this->groupRepository->incrementMembersCount($group->id);

        return $member;
    }

    public function leave(Group $group, User $user): bool
    {
        if ($group->isOwner($user)) {
            throw new RuntimeException('The owner cannot leave the group. Transfer ownership or delete the group.');
        }

        $result = $this->groupRepository->removeMember($group->id, $user->id);

        if ($result) {
            $this->groupRepository->decrementMembersCount($group->id);
        }

        return $result;
    }

    public function invite(Group $group, User $inviter, string $email): GroupInvitation
    {
        return GroupInvitation::create([
            'group_id' => $group->id,
            'invited_by' => $inviter->id,
            'email' => $email,
            'token' => Str::random(64),
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ]);
    }

    public function acceptInvitation(string $token, User $user): GroupMember
    {
        $invitation = GroupInvitation::where('token', $token)->firstOrFail();

        if (!$invitation->isPending()) {
            throw new RuntimeException('This invitation is no longer valid.');
        }

        if ($invitation->isExpired()) {
            throw new RuntimeException('This invitation has expired.');
        }

        $invitation->update(['status' => 'accepted']);

        $group = $invitation->group;

        if ($group->isMember($user)) {
            throw new RuntimeException('You are already a member of this group.');
        }

        $member = $this->groupRepository->addMember($group->id, $user->id, 'member');
        $this->groupRepository->incrementMembersCount($group->id);

        return $member;
    }

    public function kickMember(Group $group, int $targetUserId, User $actor): bool
    {
        $actorRole = $group->getMemberRole($actor);

        if (!in_array($actorRole, ['owner', 'moderator']) && !$actor->isAdmin()) {
            throw new RuntimeException('You do not have permission to kick members.');
        }

        if ($group->owner_id === $targetUserId) {
            throw new RuntimeException('Cannot kick the group owner.');
        }

        $result = $this->groupRepository->removeMember($group->id, $targetUserId);

        if ($result) {
            $this->groupRepository->decrementMembersCount($group->id);
        }

        return $result;
    }

    public function updateMemberRole(Group $group, int $targetUserId, string $role, User $actor): bool
    {
        if (!$group->isOwner($actor) && !$actor->isAdmin()) {
            throw new RuntimeException('Only the group owner can change member roles.');
        }

        if ($group->owner_id === $targetUserId) {
            throw new RuntimeException('Cannot change the role of the group owner.');
        }

        return $this->groupRepository->updateMemberRole($group->id, $targetUserId, $role);
    }

    public function uploadAvatar(Group $group, UploadedFile $file): string
    {
        if ($group->avatar) {
            Storage::disk('public')->delete($group->avatar);
        }

        $path = $file->store('groups/avatars', 'public');
        $group->update(['avatar' => $path]);

        return $path;
    }

    private function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (Group::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
