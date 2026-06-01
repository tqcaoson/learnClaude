<?php

namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationRepository
{
    public function getForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Notification::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function getUnreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    public function markRead(int $notificationId, int $userId): bool
    {
        return (bool) Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function markAllRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function create(array $data): Notification
    {
        return Notification::create($data);
    }
}
