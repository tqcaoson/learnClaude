<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService
{
    public function search(string $query, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $q = Post::with(['user', 'tags'])
            ->where('status', 'published')
            ->whereNull('hidden_at')
            ->whereNull('deleted_at');

        if (strlen($query) >= 3) {
            try {
                $q->whereRaw('MATCH(title, content) AGAINST(? IN BOOLEAN MODE)', [$query . '*']);
            } catch (\Exception $e) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('title', 'LIKE', "%$query%")
                        ->orWhere('content', 'LIKE', "%$query%");
                });
            }
        }

        if (!empty($filters['tag'])) {
            $q->whereHas('tags', fn($t) => $t->where('slug', $filters['tag']));
        }

        if (!empty($filters['author'])) {
            $q->whereHas('user', fn($u) => $u->where('name', 'LIKE', "%{$filters['author']}%"));
        }

        return $q->orderByDesc('created_at')->paginate($perPage);
    }
}
