<div class="mt-4" id="subscribers">
    @if(auth()->id() !== $channel->user_id)
        <button wire:click="toggleSubscribe"
                class="btn shadow-sm rounded-pill d-inline-flex align-items-center gap-2 px-4 py-2
                       {{ $subscribed ? 'btn-danger' : 'btn-primary' }}">
            @if($subscribed)
                <i class="bi bi-x-lg"></i>
                {{ __('messages.unsubscribe') }}
            @else
                <i class="bi bi-plus-lg"></i>
                {{ __('messages.subscribe_now') }}
            @endif
        </button>
    @endif

    <p class="mt-3 text-muted small">
        📢 <span class="fw-semibold text-body">{{ $subscriberCount }}</span> {{ __('messages.subscribers') }}
    </p>
</div>