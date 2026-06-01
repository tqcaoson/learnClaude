<?php

namespace App\Actions\Post;

use App\DTOs\PostDTO;
use App\Models\Post;
use App\Models\User;
use App\Repositories\TagRepository;
use App\Services\PostService;
use Illuminate\Http\UploadedFile;

class CreatePostAction
{
    public function __construct(
        private PostService $postService,
        private TagRepository $tagRepository,
    ) {}

    public function execute(PostDTO $dto, User $user, ?UploadedFile $featuredImage = null): Post
    {
        if ($featuredImage) {
            $dto = new PostDTO(
                title: $dto->title,
                content: $dto->content,
                status: $dto->status,
                tags: $dto->tags,
                boardId: $dto->boardId,
                featuredImagePath: $featuredImage->store('posts', 'public'),
            );
        }

        $post = $this->postService->create($dto, $user);

        if (!empty($dto->tags)) {
            $tagIds = collect($dto->tags)
                ->filter()
                ->map(fn (string $name) => $this->tagRepository->findOrCreateByName(trim($name))->id)
                ->unique()
                ->values()
                ->all();

            $post->tags()->sync($tagIds);
        }

        return $post;
    }
}
