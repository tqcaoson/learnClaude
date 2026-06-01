<?php

namespace App\Actions\Profile;

use App\DTOs\ProfileDTO;
use App\Models\User;

class UpdateProfileAction
{
    public function execute(User $user, ProfileDTO $dto): User
    {
        $user->update([
            'name' => $dto->name ?? $user->name,
            'bio' => $dto->bio,
            'location' => $dto->location,
            'website_url' => $dto->website_url,
        ]);

        return $user->fresh();
    }
}
