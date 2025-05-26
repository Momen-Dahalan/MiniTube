<div class="mt-5">
<form wire:submit.prevent="addComment" class="mb-4">
    @if($parentId)
        <div class="alert alert-info d-flex justify-content-between align-items-center border-start border-4 border-primary">
            <div>
                <small class="text-muted">
                    💬 {{ __('messages.replying_to_comment') }} <span class="fw-bold text-primary">#{{ $parentId }}</span>
                </small>
            </div>
            <button type="button" wire:click="$set('parentId', null)" class="btn btn-sm btn-outline-danger">
                ✖ {{ __('messages.cancel') }}
            </button>
        </div>
    @endif

    <div class="mb-3">
        <textarea wire:model.defer="content" rows="3" class="form-control" placeholder="💬 {{ __('messages.write_comment') }}"></textarea>
        @error('content') 
            <div class="text-danger small mt-1">{{ $message }}</div> 
        @enderror
    </div>

    <button type="submit" class="btn btn-primary rounded-pill">
        {{ $parentId ? '↩️ ' . __('messages.send_reply') : '➕ ' . __('messages.add_comment') }}
    </button>
</form>

@foreach($comments as $comment)
    <div class="mb-4 p-3 border rounded shadow-sm bg-light">
        <div class="d-flex justify-content-between">
            <div>
                <strong>{{ $comment->user->name }}</strong>
                <small class="text-muted">- {{ $comment->created_at->diffForHumans() }}</small>
            </div>
        </div>

        @if($editingCommentId === $comment->id)
            <div class="mt-2">
                <textarea wire:model.defer="editingContent" rows="3" class="form-control"></textarea>
                @error('editingContent') 
                    <div class="text-danger small mt-1">{{ $message }}</div> 
                @enderror
            </div>
            <div class="mt-2 d-flex gap-2">
                <button wire:click="update" class="btn btn-success btn-sm">💾 {{ __('messages.save') }}</button>
                <button wire:click="cancel" class="btn btn-secondary btn-sm">✖ {{ __('messages.cancel') }}</button>
            </div>
        @else
            <p class="mt-2">{{ $comment->content }}</p>

            <div class="d-flex align-items-center gap-3 flex-wrap">
                @if($comment->user_id === auth()->id())
                    <button wire:click="edit({{ $comment->id }})" class="btn btn-sm btn-outline-primary">✏️ {{ __('messages.edit') }}</button>
                    <button wire:click="deleteComment({{ $comment->id }})" class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('{{ __('messages.confirm_delete_comment') }}')">🗑 {{ __('messages.delete') }}</button>
                @endif

                @livewire('comment-like', ['comment' => $comment], key($comment->id))

                <button wire:click="setReply({{ $comment->id }})" class="btn btn-sm btn-outline-secondary">💬 {{ __('messages.reply') }}</button>
            </div>
        @endif

        @foreach($comment->replies as $reply)
            <div class="mt-3 ms-4 ps-3 border-start border-3 border-secondary">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>{{ $reply->user->name }}</strong>
                        <small class="text-muted">- {{ $reply->created_at->diffForHumans() }}</small>
                    </div>
                </div>

                @if($editingCommentId === $reply->id)
                    <div class="mt-2">
                        <textarea wire:model.defer="editingContent" rows="3" class="form-control"></textarea>
                        @error('editingContent') 
                            <div class="text-danger small mt-1">{{ $message }}</div> 
                        @enderror
                    </div>
                    <div class="mt-2 d-flex gap-2">
                        <button wire:click="update" class="btn btn-success btn-sm">💾 {{ __('messages.save') }}</button>
                        <button wire:click="cancel" class="btn btn-secondary btn-sm">✖ {{ __('messages.cancel') }}</button>
                    </div>
                @else
                    <p class="mt-2">{{ $reply->content }}</p>

                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        @if($reply->user_id === auth()->id())
                            <button wire:click="edit({{ $reply->id }})" class="btn btn-sm btn-outline-primary">✏️ {{ __('messages.edit') }}</button>
                            <button wire:click="deleteComment({{ $reply->id }})" class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('{{ __('messages.confirm_delete_reply') }}')">🗑 {{ __('messages.delete') }}</button>
                        @endif

                        @livewire('comment-like', ['comment' => $reply], key($reply->id))
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endforeach
</div>
