<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class LoginDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public string $ip_address,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            email: $request->string('email')->trim()->lower()->value(),
            password: $request->string('password')->value(),
            ip_address: $request->ip() ?? '0.0.0.0',
        );
    }
}
