<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class ProfileDTO
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $bio,
        public readonly ?string $location,
        public readonly ?string $website_url,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            bio: $request->input('bio'),
            location: $request->input('location'),
            website_url: $request->input('website_url'),
        );
    }
}
