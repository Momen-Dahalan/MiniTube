<div id="comment-{{ $comment->id }}" class="mb-4 border rounded p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <strong>{{ $comment->user->name }}</strong>
        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
    </div>

    <p class="mb-2">{{ $comment->content }}</p>

    <!-- زر الرد -->
    <button type="button" 
            class="btn btn-link p-0 reply-btn" 
            data-comment-id="{{ $comment->id }}">
        <i class="bi bi-reply"></i> {{ __('messages.reply') }}
    </button>

    <!-- الردود -->
    @if($comment->replies && $comment->replies->count())
        <div class="ms-4 mt-3">
            @foreach($comment->replies as $reply)
                @include('comments.comment', ['comment' => $reply])
            @endforeach
        </div>
    @endif
</div>
