<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteMemberRequest;
use App\Http\Requests\UpdateMemberRoleRequest;
use App\Models\GroupMember;
use App\Repositories\GroupRepository;
use App\Services\GroupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class GroupMemberController extends Controller
{
    public function __construct(
        private GroupRepository $groupRepository,
        private GroupService $groupService,
    ) {}

    public function index(string $slug): View
    {
        $group = $this->groupRepository->findBySlug($slug);

        if (!$group) {
            abort(404);
        }

        $members = GroupMember::where('group_id', $group->id)
            ->with('user')
            ->paginate(20);

        return view('groups.members', compact('group', 'members'));
    }

    public function join(string $slug): RedirectResponse
    {
        $group = $this->groupRepository->findBySlug($slug);

        if (!$group) {
            abort(404);
        }

        try {
            $this->groupService->join($group, auth()->user());
            return redirect()->route('groups.show', $slug)
                ->with('success', 'You have joined the group!');
        } catch (\RuntimeException $e) {
            return redirect()->route('groups.show', $slug)
                ->with('error', $e->getMessage());
        }
    }

    public function leave(string $slug): RedirectResponse
    {
        $group = $this->groupRepository->findBySlug($slug);

        if (!$group) {
            abort(404);
        }

        try {
            $this->groupService->leave($group, auth()->user());
            return redirect()->route('groups.index')
                ->with('success', 'You have left the group.');
        } catch (\RuntimeException $e) {
            return redirect()->route('groups.show', $slug)
                ->with('error', $e->getMessage());
        }
    }

    public function invite(InviteMemberRequest $request, string $slug): RedirectResponse
    {
        $group = $this->groupRepository->findBySlug($slug);

        if (!$group) {
            abort(404);
        }

        Gate::authorize('manageMember', $group);

        $this->groupService->invite($group, auth()->user(), $request->input('email'));

        return redirect()->route('groups.members', $slug)
            ->with('success', 'Invitation sent successfully!');
    }

    public function kick(string $slug, int $userId): RedirectResponse
    {
        $group = $this->groupRepository->findBySlug($slug);

        if (!$group) {
            abort(404);
        }

        Gate::authorize('manageMember', $group);

        try {
            $this->groupService->kickMember($group, $userId, auth()->user());
            return redirect()->route('groups.members', $slug)
                ->with('success', 'Member removed from group.');
        } catch (\RuntimeException $e) {
            return redirect()->route('groups.members', $slug)
                ->with('error', $e->getMessage());
        }
    }

    public function updateRole(UpdateMemberRoleRequest $request, string $slug, int $userId): RedirectResponse
    {
        $group = $this->groupRepository->findBySlug($slug);

        if (!$group) {
            abort(404);
        }

        Gate::authorize('manageMember', $group);

        try {
            $this->groupService->updateMemberRole($group, $userId, $request->input('role'), auth()->user());
            return redirect()->route('groups.members', $slug)
                ->with('success', 'Member role updated.');
        } catch (\RuntimeException $e) {
            return redirect()->route('groups.members', $slug)
                ->with('error', $e->getMessage());
        }
    }

    public function acceptInvitation(string $token): RedirectResponse
    {
        if (!auth()->check()) {
            return redirect()->route('auth.login')
                ->with('warning', 'Please login to accept the invitation.');
        }

        try {
            $member = $this->groupService->acceptInvitation($token, auth()->user());
            return redirect()->route('groups.show', $member->group->slug)
                ->with('success', 'You have successfully joined the group!');
        } catch (\RuntimeException $e) {
            return redirect()->route('groups.index')
                ->with('error', $e->getMessage());
        }
    }
}
