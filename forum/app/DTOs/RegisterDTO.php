<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class RegisterDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public ?string $name = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            email: $request->string('email')->trim()->lower()->value(),
            password: $request->string('password')->value(),
            name: $request->string('name')->trim()->value() ?: null,
        );
    }
}
