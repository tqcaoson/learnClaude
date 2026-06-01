<?php

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UploadAvatarAction
{
    public function execute(User $user, UploadedFile $file): string
    {
        $validator = validator(['avatar' => $file], [
            'avatar' => ['required', 'image', 'max:2048', 'mimes:jpeg,png,webp'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $file->store('avatars', 'public');

        $user->update(['avatar' => $path]);

        return Storage::disk('public')->url($path);
    }
}
