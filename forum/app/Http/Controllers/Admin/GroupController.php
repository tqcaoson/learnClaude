<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function index(Request $request): View
    {
        $query = Group::with('owner');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $groups = $query->latest()->paginate(20);

        return view('admin.groups.index', compact('groups'));
    }

    public function show(string $slug): View
    {
        $group = Group::where('slug', $slug)
            ->with(['owner', 'groupMembers.user'])
            ->firstOrFail();

        return view('admin.groups.show', compact('group'));
    }

    public function suspend(string $slug): RedirectResponse
    {
        $group = Group::where('slug', $slug)->firstOrFail();
        $group->update(['status' => 'suspended']);

        return redirect()->route('admin.groups.index')
            ->with('success', "Group \"{$group->name}\" has been suspended.");
    }

    public function activate(string $slug): RedirectResponse
    {
        $group = Group::where('slug', $slug)->firstOrFail();
        $group->update(['status' => 'active']);

        return redirect()->route('admin.groups.index')
            ->with('success', "Group \"{$group->name}\" has been activated.");
    }

    public function destroy(string $slug): RedirectResponse
    {
        $group = Group::where('slug', $slug)->firstOrFail();
        $group->delete();

        return redirect()->route('admin.groups.index')
            ->with('success', "Group \"{$group->name}\" has been deleted.");
    }
}
