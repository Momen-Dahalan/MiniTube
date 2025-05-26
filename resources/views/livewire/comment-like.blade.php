<button wire:click="toggleLike"
    class="btn d-flex align-items-center gap-2 rounded-pill shadow-sm px-3 py-2 
    {{ $liked ? 'btn-pink text-white' : 'btn-outline-secondary text-muted' }}"
    style="{{ $liked ? 'background-color: #ec4899; border-color: #ec4899;' : '' }}">

    <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-heart-fill" width="20" height="20"
        fill="{{ $liked ? 'white' : 'none' }}" stroke="{{ $liked ? 'white' : 'currentColor' }}" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4.318 6.318a4.5 4.5 0 0 1 6.364 0L12 7.636l1.318-1.318a4.5 
               4.5 0 1 1 6.364 6.364L12 21.364l-7.682-7.682a4.5 
               4.5 0 0 1 0-6.364z" />
    </svg>

    <span>
        {{ $comment->likes()->where('is_like', true)->count() }}
        {{ __('messages.likes') }}
    </span>
</button>
