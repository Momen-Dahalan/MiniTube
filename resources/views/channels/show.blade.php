@extends('layouts.main')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">

            <!-- صورة القناة والمعلومات -->
            <div class="d-flex flex-column align-items-center text-center mb-5">
                <img src="{{ asset('storage/' . ($channel->image ?? 'default-avatar.png')) }}" 
                    alt="{{ __('message.channel_image_alt') }}"
                    class="rounded-circle border border-3 border-primary"
                    style="width: 100px; height: 100px; object-fit: cover;">
                    
                <h2 class="fw-bold text-primary mt-3">{{ $channel->name }}</h2>
                <p class="text-muted mb-3">
                    {{ $channel->description ?? __('message.no_channel_description') }}
                </p>

                <!-- أزرار الإدارة -->
                @auth
                @if(auth()->id() === $channel->user_id)
                <div class="d-flex flex-column gap-2" style="width: 300px;">
                    <a href="{{ route('videos.create', $channel->id) }}" 
                    class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-upload"></i> {{ __('message.upload_video') }}
                    </a>

                    <a href="{{ route('channels.edit', $channel->id) }}" 
                    class="btn btn-warning text-white d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-pencil-square"></i> {{ __('message.edit_channel') }}
                    </a>

                    <form id="deleteChannelForm" action="{{ route('channels.destroy', $channel->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                                onclick="confirmDelete()"
                                class="btn btn-danger d-flex align-items-center justify-content-center gap-2 w-100">
                            <i class="bi bi-trash"></i> {{ __('message.delete_channel') }}
                        </button>
                    </form>
                </div>
                @endif
                @endauth
            </div>

            <!-- قائمة الفيديوهات -->
            <h3 class="fs-4 fw-bold mb-4">{{ __('message.videos') }}</h3>

            <div class="row row-cols-1 row-cols-md-3 g-4">
                @forelse($videos as $video)
                <div class="col">
                    <a href="{{ route('videos.show', $video) }}" class="card h-100 text-decoration-none text-dark shadow-sm border-0 rounded-4 overflow-hidden hover-card">
                        <img src="{{ asset('storage/' . $video->thumbnail) }}" 
                             alt="{{ __('message.video_thumbnail_alt') }}" 
                             class="card-img-top"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="fw-bold mb-2 text-truncate">{{ $video->title }}</h5>
                            <p class="text-muted">{{ Str::limit($video->description, 100) }}</p>
                        </div>
                    </a>
                </div>
                @empty
                    <p class="text-muted">{{ __('message.no_videos') }}</p>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $videos->links() }}
            </div>

        </div>
    </div>
</div>

<!-- SweetAlert Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete() {
        Swal.fire({
            title: '{{ __("message.confirm_title") }}',
            text: "{{ __('message.confirm_text') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ __("message.confirm_button") }}',
            cancelButtonText: '{{ __("message.cancel_button") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteChannelForm').submit();
            }
        });
    }
</script>

<style>
    .hover-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12);
        transition: all 0.4s ease-in-out;
    }

    .hover-card img {
        transition: transform 0.4s ease-in-out;
    }

    .hover-card img:hover {
        transform: scale(1.05);
    }
</style>
@endsection
