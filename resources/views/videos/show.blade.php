@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">

            {{-- العنوان وعدد المشاهدات --}}
            <div class="mb-4">
                <h1 class="display-5 fw-bold text-dark">{{ $video->title }}</h1>
                <div class="d-flex align-items-center gap-2 text-muted small mt-2">
                    <i class="bi bi-eye-fill text-primary"></i>
                    <span>{{ number_format($video->views) }} {{ __('messages.views') }}</span>
                </div>

                @if($video->category)
                    <span class="badge bg-primary-subtle text-primary mt-3 px-3 py-2 rounded-pill">
                        <i class="bi bi-tag-fill me-1"></i>{{ $video->category->name }}
                    </span>
                @endif
            </div>

            {{-- مشغل الفيديو --}}
            @php
                $videoPaths = $video->video_paths ? json_decode($video->video_paths, true) : [];
                $defaultVideo = Storage::disk('s3')->url($video->video_path);
                $firstPath = count($videoPaths) ? reset($videoPaths) : null;
                $firstQualityUrl = $firstPath ? Storage::disk('s3')->url($firstPath) : $defaultVideo;
            @endphp

            <div class="ratio ratio-16x9 rounded-3 overflow-hidden border mb-4 shadow-sm">
                <video id="video-player" controls preload="metadata" class="w-100 h-100">
                    <source id="video-source" src="{{ $firstQualityUrl }}" type="video/mp4">
                    {{ __('messages.browser_no_support') }}
                </video>
            </div>

            {{-- اختيار الجودة --}}
            @if(!empty($videoPaths))
                <div class="mb-4">
                    <h5 class="fw-semibold text-dark">{{ __('messages.choose_quality') }}</h5>
                    <div class="btn-group flex-wrap mt-2" role="group">
                        @foreach($videoPaths as $quality => $path)
                            <button type="button"
                                class="btn btn-outline-secondary quality-btn mb-2 px-3 py-1"
                                data-src="{{ Storage::disk('s3')->url($path) }}">
                                {{ strtoupper($quality) }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- وصف الفيديو --}}
            @if($video->description)
                <div class="bg-light border-start border-4 border-primary p-4 rounded-3 mb-4 shadow-sm">
                    <h5 class="fw-bold text-primary mb-2">
                        <i class="bi bi-info-circle-fill me-1"></i>{{ __('messages.description') }}
                    </h5>
                    <p class="mb-0 text-secondary lh-lg">{{ $video->description }}</p>
                </div>
            @endif

            {{-- أزرار التعديل والحذف --}}
            @can('update' , $video)
                <div class="mb-4 d-flex flex-wrap gap-2">
                    <a href="{{ route('videos.edit', $video) }}" class="btn btn-outline-primary">
                        <i class="bi bi-pencil-square me-1"></i>{{ __('messages.edit') }}
                    </a>
                    <form action="{{ route('videos.destroy', $video) }}" method="POST"
                          onsubmit="return confirm('{{ __('messages.confirm_delete') }}');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger" type="submit">
                            <i class="bi bi-trash me-1"></i>{{ __('messages.delete') }}
                        </button>
                    </form>
                </div>   
            @endcan

            {{-- التفاعل --}}
            <div class="mb-5">
                @auth
                    <div class="d-flex align-items-center gap-4 flex-wrap mb-3">
                        <livewire:video-likes :video="$video" :wire:key="'video-likes-'.$video->id" />
                    </div>
                    <div>
                        <livewire:channel-subscribe :channel="$video->channel" />
                    </div>
                @else
                    <div class="alert alert-warning small">{{ __('messages.login_to_interact') }}</div>
                @endauth
            </div>

            {{-- التعليقات --}}
            <div>
                <h4 class="mb-4 text-dark fw-bold">
                    <i class="bi bi-chat-left-dots-fill text-primary me-2"></i> {{ __('messages.comments') }}
                </h4>

                @auth
                    <form action="{{ route('comments.store') }}" method="POST" class="mb-4">
                        @csrf
                        <input type="hidden" name="video_id" value="{{ $video->id }}">
                        <input type="hidden" name="parent_id" id="parent_id" value="">

                        <div class="mb-3">
                            <label for="content" class="form-label">{{ __('messages.add_comment') }}</label>
                            <textarea name="content" id="content" rows="3"
                                      class="form-control @error('content') is-invalid @enderror" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('messages.send_comment') }}</button>
                            <button type="button" id="cancel-reply" class="btn btn-secondary d-none">{{ __('messages.cancel_reply') }}</button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning small">{{ __('messages.login_to_comment') }}</div>
                @endauth

                {{-- التعليقات --}}
                @if($video->comments->count())
                    <div class="comments-list">
                        @foreach($video->comments->where('parent_id', null)->sortByDesc('created_at') as $comment)
                            @include('comments.comment', ['comment' => $comment])
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">{{ __('messages.no_comments') }}</p>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- سكربت تغيير الجودة --}}
<script>
    document.querySelectorAll('.quality-btn').forEach(button => {
        button.addEventListener('click', () => {
            const video = document.getElementById('video-player');
            const source = document.getElementById('video-source');
            const currentTime = video.currentTime;
            const wasPlaying = !video.paused;

            source.src = button.dataset.src;
            video.load();
            video.currentTime = currentTime;
            if (wasPlaying) video.play();

            document.querySelectorAll('.quality-btn').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        });
    });

    const firstQualityBtn = document.querySelector('.quality-btn');
    if (firstQualityBtn) firstQualityBtn.classList.add('active');
</script>

{{-- سكربت الرد على التعليق --}}
<script>
    document.querySelectorAll('.reply-btn').forEach(button => {
        button.addEventListener('click', () => {
            const commentId = button.dataset.commentId;
            document.getElementById('parent_id').value = commentId;
            document.getElementById('content').focus();
            document.getElementById('cancel-reply').classList.remove('d-none');
        });
    });

    document.getElementById('cancel-reply').addEventListener('click', () => {
        document.getElementById('parent_id').value = '';
        document.getElementById('cancel-reply').classList.add('d-none');
    });
</script>
@endsection
