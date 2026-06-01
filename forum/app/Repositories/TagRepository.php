<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class TagRepository
{
    public function findOrCreateByName(string $name): Tag
    {
        $slug = Str::slug($name);

        return Tag::firstOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'slug' => $slug]
        );
    }

    public function findBySlug(string $slug): ?Tag
    {
        return Tag::where('slug', $slug)->first();
    }

    public function getPopular(int $limit = 20): Collection
    {
        return Tag::withCount('posts')
            ->orderByDesc('posts_count')
            ->limit($limit)
            ->get();
    }
}
