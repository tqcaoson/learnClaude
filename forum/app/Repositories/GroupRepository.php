<?php

namespace App\Repositories;

use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class GroupRepository
{
    public function create(array $data): Group
    {
        return Group::create($data);
    }

    public function findBySlug(string $slug): ?Group
    {
        return Group::where('slug', $slug)->first();
    }

    public function getPublicPaginated(int $perPage = 12, ?string $search = null): LengthAwarePaginator
    {
        $query = Group::where('type', 'public')
            ->where('status', 'active')
            ->with('owner');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function getUserGroups(int $userId): Collection
    {
        return GroupMember::where('user_id', $userId)
            ->with('group.owner')
            ->get();
    }

    public function addMember(int $groupId, int $userId, string $role = 'member'): GroupMember
    {
        return GroupMember::create([
            'group_id' => $groupId,
            'user_id' => $userId,
            'role' => $role,
            'joined_at' => now(),
        ]);
    }

    public function removeMember(int $groupId, int $userId): bool
    {
        return GroupMember::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->delete() > 0;
    }

    public function updateMemberRole(int $groupId, int $userId, string $role): bool
    {
        return GroupMember::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->update(['role' => $role]) > 0;
    }

    public function incrementMembersCount(int $groupId): void
    {
        Group::where('id', $groupId)->increment('members_count');
    }

    public function decrementMembersCount(int $groupId): void
    {
        Group::where('id', $groupId)->decrement('members_count');
    }
}
