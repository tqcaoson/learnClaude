<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class GroupDTO
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description = null,
        public readonly string $type = 'public',
        public readonly ?string $avatarPath = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            description: $request->input('description'),
            type: $request->input('type', 'public'),
            avatarPath: $request->input('avatar_path'),
        );
    }
}
