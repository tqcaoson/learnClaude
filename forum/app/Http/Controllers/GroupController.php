<?php

namespace App\Http\Controllers;

use App\Actions\Group\CreateGroupAction;
use App\Actions\Group\UpdateGroupAction;
use App\DTOs\GroupDTO;
use App\Http\Requests\GroupRequest;
use App\Models\Group;
use App\Repositories\GroupRepository;
use App\Services\GroupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function __construct(
        private GroupRepository $groupRepository,
        private GroupService $groupService,
        private CreateGroupAction $createGroupAction,
        private UpdateGroupAction $updateGroupAction,
    ) {}

    public function index(Request $request): View
    {
        $search = $request->input('search');
        $groups = $this->groupRepository->getPublicPaginated(12, $search);

        return view('groups.index', compact('groups', 'search'));
    }

    public function show(string $slug): View
    {
        $group = $this->groupRepository->findBySlug($slug);

        if (!$group) {
            abort(404);
        }

        if (auth()->check()) {
            Gate::authorize('view', $group);
        } elseif (!$group->isPublic()) {
            abort(403, 'This is a private group.');
        }

        $posts = $group->posts()->with('user')->latest()->paginate(10);

        return view('groups.show', compact('group', 'posts'));
    }

    public function create(): View
    {
        return view('groups.create');
    }

    public function store(GroupRequest $request): RedirectResponse
    {
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('groups/avatars', 'public');
        }

        $dto = new GroupDTO(
            name: $request->input('name'),
            description: $request->input('description'),
            type: $request->input('type', 'public'),
            avatarPath: $avatarPath,
        );

        $group = $this->createGroupAction->execute(auth()->user(), $dto);

        return redirect()->route('groups.show', $group->slug)
            ->with('success', 'Group created successfully!');
    }

    public function edit(string $slug): View
    {
        $group = $this->groupRepository->findBySlug($slug);

        if (!$group) {
            abort(404);
        }

        Gate::authorize('update', $group);

        return view('groups.edit', compact('group'));
    }

    public function update(GroupRequest $request, string $slug): RedirectResponse
    {
        $group = $this->groupRepository->findBySlug($slug);

        if (!$group) {
            abort(404);
        }

        Gate::authorize('update', $group);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('groups/avatars', 'public');
        }

        $dto = new GroupDTO(
            name: $request->input('name'),
            description: $request->input('description'),
            type: $request->input('type', 'public'),
            avatarPath: $avatarPath,
        );

        $this->updateGroupAction->execute($group, $dto);

        return redirect()->route('groups.show', $group->slug)
            ->with('success', 'Group updated successfully!');
    }

    public function destroy(string $slug): RedirectResponse
    {
        $group = $this->groupRepository->findBySlug($slug);

        if (!$group) {
            abort(404);
        }

        Gate::authorize('delete', $group);

        $this->groupService->delete($group);

        return redirect()->route('groups.index')
            ->with('success', 'Group deleted successfully!');
    }
}
