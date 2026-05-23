<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class CommentDTO
{
    public function __construct(
        public string $content,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            content: $request->string('content')->trim()->value(),
        );
    }
}
