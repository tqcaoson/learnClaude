<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use App\Repositories\GroupRepository;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function __construct(
        private GroupRepository $groupRepository,
    ) {}

    public function index(): View
    {
        $memberships = $this->groupRepository->getUserGroups(auth()->id());

        return view('my.groups', compact('memberships'));
    }
}
