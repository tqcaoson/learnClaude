<?php

namespace App\Services;

use App\Actions\Profile\UpdateProfileAction;
use App\Actions\Profile\UploadAvatarAction;
use App\DTOs\ProfileDTO;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class ProfileService
{
    public function __construct(
        private UpdateProfileAction $updateProfileAction,
        private UploadAvatarAction $uploadAvatarAction,
    ) {}

    public function updateProfile(User $user, ProfileDTO $dto): User
    {
        return $this->updateProfileAction->execute($user, $dto);
    }

    public function uploadAvatar(User $user, UploadedFile $file): string
    {
        return $this->uploadAvatarAction->execute($user, $file);
    }
}
