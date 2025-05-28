@extends('layouts.main')

@section('content')
    <div class="container py-5 d-flex flex-column align-items-center">
        {{-- صندوق البحث مع عرض محدود ومركز --}}
        <form action="{{ route('search') }}" method="GET" class="d-flex mb-4 w-100" style="max-width: 800px;">
            <input type="text" name="query" value="{{ request('query') }}"
                placeholder="{{ __('messages.search_placeholder') }}" class="form-control me-2" required>
            <button type="submit" class="btn btn-primary">{{ __('messages.search_button') }}</button>
        </form>

        <h1 class="text-center mb-5 fw-bold text-primary w-100" style="max-width: 800px;">
            {{ __('messages.videos_list_title') }}
        </h1>

        @if ($videos->count())
            <div class="row g-4 w-100 justify-content-center" style="max-width: 1100px;">
                @foreach ($videos as $video)
                    @if ($video->processed)
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="card h-100 shadow-sm rounded-4 border-0 hover-card overflow-hidden">
                                {{-- صورة الفيديو --}}
                                <a href="{{ route('videos.show', $video) }}">
                                    <img src="{{ asset('storage/thumnail/' . $video->thumbnail) }}"
                                        alt="{{ $video->title }}" class="card-img-top video-thumbnail"
                                        style="height: 200px; object-fit: cover;">
                                </a>

                                <div class="card-body d-flex flex-column">
                                    {{-- اسم القناة مع الصورة --}}
                                    <div class="d-flex align-items-center mb-3">
                                        <a href="{{ route('channels.show', $video->channel->id) }}">
                                            <img src="{{ asset('storage/' . ($video->channel->image ?? 'default-avatar.png')) }}"
                                                alt="{{ $video->channel->name }}"
                                                class="rounded-circle me-3 border border-2 border-primary channel-img">
                                        </a>
                                        <h6 class="mb-0 text-truncate fw-semibold" style="max-width: calc(100% - 50px);">
                                            {{ $video->channel->name }}
                                        </h6>
                                    </div>

                                    {{-- عنوان الفيديو --}}
                                    <h5 class="fw-bold mb-2 text-truncate" title="{{ $video->title }}">
                                        {{ Str::limit($video->title, 50) }}
                                    </h5>

                                    {{-- وصف الفيديو --}}
                                    <p class="card-text text-muted flex-grow-1"
                                        style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ Str::limit($video->description, 120) }}
                                    </p>

                                    {{-- زر عرض الفيديو --}}
                                    <div class="mt-3 text-center">
                                        <a href="{{ route('videos.show', $video) }}"
                                            class="btn btn-outline-primary btn-sm px-4 fw-semibold">
                                            <i class="bi bi-play-circle"></i> {{ __('messages.watch_video') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="mt-5 d-flex justify-content-center w-100" style="max-width: 1100px;">
                {{ $videos->links() }}
            </div>
        @else
            <p class="text-center text-muted fs-5" style="max-width: 800px;">
                @if (request('query'))
                    {{ __('messages.no_results_for_query', ['query' => request('query')]) }}
                @else
                    {{ __('messages.no_videos') }}
                @endif
            </p>
        @endif
    </div>

    {{-- تنسيقات إضافية --}}
    <style>
        .video-thumbnail {
            transition: transform 0.3s ease;
        }

        .video-thumbnail:hover {
            transform: scale(1.05);
        }

        .channel-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .channel-img:hover {
            transform: scale(1.15);
        }

        .hover-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        }
    </style>
@endsection
