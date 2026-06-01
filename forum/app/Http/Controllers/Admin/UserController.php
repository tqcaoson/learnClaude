<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function show(int $id)
    {
        $user = User::findOrFail($id);
        $postsCount = $user->posts()->count();
        $commentsCount = $user->comments()->count();
        $recentPosts = $user->posts()->latest()->limit(5)->get();

        return view('admin.users.show', compact('user', 'postsCount', 'commentsCount', 'recentPosts'));
    }

    public function updateRole(Request $request, int $id)
    {
        $request->validate([
            'role' => ['required', 'string', 'in:user,moderator,admin'],
        ]);

        if (auth()->id() === $id) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user = User::findOrFail($id);
        $user->update(['role' => $request->input('role')]);

        return back()->with('success', 'User role updated successfully.');
    }

    public function ban(int $id)
    {
        if (auth()->id() === $id) {
            return back()->with('error', 'You cannot ban yourself.');
        }

        $user = User::findOrFail($id);
        $user->update(['status' => 'banned']);

        return back()->with('success', 'User has been banned.');
    }

    public function activate(int $id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);

        return back()->with('success', 'User has been activated.');
    }

    public function destroy(int $id)
    {
        if (auth()->id() === $id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            return back()->with('error', 'Admin accounts cannot be deleted.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
