<?php

namespace App\Http\Controllers;

use App\DTOs\ProfileDTO;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(
        private ProfileService $profileService,
    ) {}

    public function show(int $id)
    {
        $user = User::findOrFail($id);
        $posts = $user->posts()->latest()->paginate(10);

        return view('profile.show', compact('user', 'posts'));
    }

    public function edit()
    {
        $user = auth()->user();

        return view('profile.edit', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = auth()->user();
        $dto = ProfileDTO::fromRequest($request);

        $this->profileService->updateProfile($user, $dto);

        return redirect()->route('profile.show', ['id' => $user->id])
            ->with('success', 'Profile updated successfully!');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048', 'mimes:jpeg,png,webp'],
        ]);

        $user = auth()->user();

        try {
            $this->profileService->uploadAvatar($user, $request->file('avatar'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to upload avatar: ' . $e->getMessage());
        }

        return redirect()->route('profile.show', ['id' => $user->id])
            ->with('success', 'Avatar updated successfully!');
    }
}
