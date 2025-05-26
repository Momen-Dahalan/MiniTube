@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-primary fw-bold">Notifications</h1>

    @if($notifications->count())
        <div class="list-group">
            @foreach($notifications as $notification)
                <a href="#" class="list-group-item list-group-item-action mb-2 rounded shadow-sm">
                    <div class="d-flex w-100 justify-content-between">
                        <p class="mb-1">
                            {{ $notification->data['message'] ?? 'New notification' }}
                        </p>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="alert alert-info" role="alert">
            No notifications yet.
        </div>
    @endif
</div>
@endsection
