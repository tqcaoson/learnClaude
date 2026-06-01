<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(
        private readonly NotificationService $notificationService
    ) {}

    public function index(Request $request): View
    {
        $userId        = auth()->id();
        $notifications = $this->notificationService->getForUser($userId);
        $unreadCount   = $this->notificationService->getUnreadCount($userId);

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markRead(int $id, Request $request): JsonResponse|RedirectResponse
    {
        $userId = auth()->id();
        $this->notificationService->markRead($id, $userId);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function markAllRead(): RedirectResponse
    {
        $this->notificationService->markAllRead(auth()->id());

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
