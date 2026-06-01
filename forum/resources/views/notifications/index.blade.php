@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                Notifications
                @if($unreadCount > 0)
                    <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
                @endif
            </h2>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        Mark All Read
                    </button>
                </form>
            @endif
        </div>

        @if($notifications->count() > 0)
            <div class="list-group mb-4">
                @foreach($notifications as $notification)
                    <div class="list-group-item list-group-item-action {{ !$notification->isRead() ? 'bg-light border-start border-primary border-3' : '' }} p-3"
                         id="notification-{{ $notification->id }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-1">
                                    {{-- Icon by type --}}
                                    @if($notification->type === 'new_comment')
                                        <span class="me-2">💬</span>
                                    @elseif($notification->type === 'reply_comment')
                                        <span class="me-2">↩️</span>
                                    @elseif($notification->type === 'mention')
                                        <span class="me-2">@</span>
                                    @else
                                        <span class="me-2">🔔</span>
                                    @endif
                                    <strong class="me-2">{{ $notification->title }}</strong>
                                    @if(!$notification->isRead())
                                        <span class="badge bg-primary ms-1" style="font-size:0.65rem;">New</span>
                                    @endif
                                </div>
                                <p class="mb-1 text-secondary small">{{ $notification->body }}</p>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="ms-3 d-flex flex-column align-items-end gap-1">
                                @if($notification->url)
                                    @if(!$notification->isRead())
                                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                View
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ $notification->url }}" class="btn btn-sm btn-outline-secondary">
                                            View
                                        </a>
                                    @endif
                                @else
                                    @if(!$notification->isRead())
                                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-link text-secondary p-0">
                                                Mark read
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <p class="fs-1">🔔</p>
                <p class="text-muted fs-5">No notifications yet.</p>
                <p class="text-muted">You will be notified when someone comments on your posts or replies to your comments.</p>
            </div>
        @endif
    </div>
</div>
@endsection
