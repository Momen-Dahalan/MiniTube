@extends('layouts.main')

@section('content')
<div class="container d-flex flex-column justify-content-center align-items-center" style="height: 70vh;">
    <h2 class="mb-4 fw-bold text-primary">{{ __('messages.processing_video') }}</h2>

    <div class="spinner-border text-indigo-600 mb-4" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">{{ __('messages.loading') }}</span>
    </div>

    <p class="text-secondary fs-5 text-center">{{ __('messages.processing_info') }}</p>
</div>

<script>
    setInterval(() => {
        fetch("{{ route('videos.status', $video->id) }}")
            .then(res => res.json())
            .then(data => {
                if (data.is_processed) {
                    window.location.href = "{{ route('videos.show', $video->id) }}";
                }
            });
    }, 5000); // كل 5 ثواني
</script>
@endsection
