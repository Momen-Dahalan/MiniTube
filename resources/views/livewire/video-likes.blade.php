<div class="d-flex gap-3 mt-4" id="likes-section">
    <!-- زر الإعجاب -->
    <button wire:click="like"
        class="btn {{ $userLikeType === true ? 'btn-success text-white' : 'btn-outline-success' }} d-flex align-items-center gap-1 rounded-pill shadow-sm">
        👍 <span>{{ $video->likesCount() }}</span>
    </button>

    <!-- زر عدم الإعجاب -->
    <button wire:click="dislike"
        class="btn {{ $userLikeType === false ? 'btn-danger text-white' : 'btn-outline-danger' }} d-flex align-items-center gap-1 rounded-pill shadow-sm">
        👎 <span>{{ $video->dislikesCount() }}</span>
    </button>
</div>
