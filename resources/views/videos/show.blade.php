@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="mx-auto col-10">
                
                {{-- العنوان وعدد المشاهدات --}}
                <div class="mb-4">
                    <h1 class="display-5 fw-bold text-dark">{{ $video->title }}</h1>
                    <div class="d-flex align-items-center gap-2 text-muted small mt-2">
                        <i class="bi bi-eye-fill text-primary"></i>
                        <span>{{ number_format($video->views) }} {{ __('messages.views') }}</span>
                    </div>

                    @if ($video->category)
                        <span class="badge bg-primary-subtle text-primary mt-3 px-3 py-2 rounded-pill">
                            <i class="bi bi-tag-fill me-1"></i>{{ $video->category->name }}
                        </span>
                    @endif
                </div>
                <div class="videocontainer">
                    @foreach ($video->convertedVideos as $video_converted)
                        <video id="videoPlayer" controls
                            style='{{ $video->Longitudinal == '0' ? 'width: 100%; height: 90%;' : 'width: 900px; height: 510px;' }}'>
                            @if ($video->quality == 1080)
                                <source id="webm_source"
                                    src="{{ Storage::disk('s3')->url($video_converted->webm_Format_1080) }}"
                                    type="video/webm">
                                <source id="mp4_source"
                                    src="{{ Storage::disk('s3')->url($video_converted->mp4_Format_1080) }}"
                                    type="video/mp4">
                            @elseif ($video->quality == 720)
                                <source id="webm_source"
                                    src="{{ Storage::disk('s3')->url($video_converted->webm_Format_720) }}"
                                    type="video/webm">
                                <source id="mp4_source"
                                    src="{{ Storage::disk('s3')->url($video_converted->mp4_Format_720) }}" type="video/mp4">
                            @elseif ($video->quality == 480)
                                <source id="webm_source"
                                    src="{{ Storage::disk('s3')->url($video_converted->webm_Format_480) }}"
                                    type="video/webm">
                                <source id="mp4_source"
                                    src="{{ Storage::disk('s3')->url($video_converted->mp4_Format_480) }}"
                                    type="video/mp4">
                            @elseif ($video->quality == 360)
                                <source id="webm_source"
                                    src="{{ Storage::disk('s3')->url($video_converted->webm_Format_360) }}"
                                    type="video/webm">
                                <source id="mp4_source"
                                    src="{{ Storage::disk('s3')->url($video_converted->mp4_Format_360) }}"
                                    type="video/mp4">
                            @else
                                <source id="webm_source"
                                    src="{{ Storage::disk('s3')->url($video_converted->webm_Format_240) }}"
                                    type="video/webm">
                                <source id="mp4_source"
                                    src="{{ Storage::disk('s3')->url($video_converted->mp4_Format_240) }}"
                                    type="video/mp4">
                            @endif
                        </video>
                    @endforeach
                </div>


                <select id='qualityPick' class=" mt-3 mb-4 ">
                    <option value="1080" {{ $video->quality == 1080 ? 'selected' : '' }}
                        {{ $video->quality < 1080 ? 'hidden' : '' }}>1080p</option>
                    <option value="720" {{ $video->quality == 720 ? 'selected' : '' }}
                        {{ $video->quality < 720 ? 'hidden' : '' }}>720p</option>
                    <option value="480" {{ $video->quality == 480 ? 'selected' : '' }}
                        {{ $video->quality < 480 ? 'hidden' : '' }}>480p</option>
                    <option value="360" {{ $video->quality == 360 ? 'selected' : '' }}
                        {{ $video->quality < 360 ? 'hidden' : '' }}>360p</option>
                    <option value="240" {{ $video->quality == 240 ? 'selected' : '' }}>240p</option>
                </select>

                {{-- وصف الفيديو --}}
                @if ($video->description)
                    <div class="bg-light border-start border-4 border-primary p-4 rounded-3 mb-4 shadow-sm mt-10">
                        <h5 class="fw-bold text-primary mb-2">
                            <i class="bi bi-info-circle-fill me-1"></i>{{ __('messages.description') }}
                        </h5>
                        <p class="mb-0 text-secondary lh-lg">{{ $video->description }}</p>
                    </div>
                @endif

                {{-- أزرار التعديل والحذف --}}
                @can('update', $video)
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
                                <textarea name="content" id="content" rows="3" class="form-control @error('content') is-invalid @enderror"
                                    required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">{{ __('messages.send_comment') }}</button>
                                <button type="button" id="cancel-reply"
                                    class="btn btn-secondary d-none">{{ __('messages.cancel_reply') }}</button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning small">{{ __('messages.login_to_comment') }}</div>
                    @endauth

                    {{-- التعليقات --}}
                    @if ($video->comments->count())
                        <div class="comments-list">
                            @foreach ($video->comments->where('parent_id', null)->sortByDesc('created_at') as $comment)
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
@endsection

@section('scripts')
    <script>
        document.getElementById("qualityPick").onchange = function() {
            changeQulity()
        };

        function changeQulity() {
            var video = document.getElementById("videoPlayer");
            curTime = video.currentTime;
            var selected = document.getElementById("qualityPick").value;

            if (selected == '1080') {
                source = document.getElementById("webm_source").src =
                    "{{ Storage::disk('s3')->url($video_converted->webm_Format_1080) }}";
                source = document.getElementById("mp4_source").src =
                    "{{ Storage::disk('s3')->url($video_converted->mp4_Format_1080) }}";
            } else if (selected == '720') {
                source = document.getElementById("webm_source").src =
                    "{{ Storage::disk('s3')->url($video_converted->webm_Format_720) }}";
                source = document.getElementById("mp4_source").src =
                    "{{ Storage::disk('s3')->url($video_converted->mp4_Format_720) }}";
            } else if (selected == '480') {
                source = document.getElementById("webm_source").src =
                    "{{ Storage::disk('s3')->url($video_converted->webm_Format_480) }}";
                source = document.getElementById("mp4_source").src =
                    "{{ Storage::disk('s3')->url($video_converted->mp4_Format_480) }}";
            } else if (selected == '360') {
                source = document.getElementById("webm_source").src =
                    "{{ Storage::disk('s3')->url($video_converted->webm_Format_360) }}";
                source = document.getElementById("mp4_source").src =
                    "{{ Storage::disk('s3')->url($video_converted->mp4_Format_360) }}";
            } else if (selected == '240') {
                source = document.getElementById("webm_source").src =
                    "{{ Storage::disk('s3')->url($video_converted->webm_Format_240) }}";
                source = document.getElementById("mp4_source").src =
                    "{{ Storage::disk('s3')->url($video_converted->mp4_Format_240) }}";
            }

            video.load();
            video.play();
            video.currentTime = curTime;

        }
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
