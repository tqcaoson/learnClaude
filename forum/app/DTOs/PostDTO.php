<?php

namespace App\DTOs;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class PostDTO
{
    public function __construct(
        public string $title,
        public string $content,
        public string $status = 'published',
        public array $tags = [],
        public ?int $boardId = null,
        public ?string $featuredImagePath = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            title: $request->string('title')->trim()->value(),
            content: $request->string('content')->trim()->value(),
            status: $request->string('status')->trim()->value() ?: 'published',
            tags: $request->input('tags', []),
            boardId: $request->input('board_id') ? (int) $request->input('board_id') : null,
            featuredImagePath: null,
        );
    }
}
