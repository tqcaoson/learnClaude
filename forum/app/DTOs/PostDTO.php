<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class PostDTO
{
    public function __construct(
        public string $title,
        public string $content,
        public string $status = 'published',
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            title: $request->string('title')->trim()->value(),
            content: $request->string('content')->trim()->value(),
            status: $request->string('status')->trim()->value() ?: 'published',
        );
    }
}
