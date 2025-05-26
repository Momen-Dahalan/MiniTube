@extends('layouts.main')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-5 fw-bold text-primary">{{ __('messages.video_list') }}</h1>

    @if($videos->count())
        <div class="row g-4">
            @foreach($videos as $video)
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="{{ route('videos.show', $video) }}" 
                       class="card h-100 shadow-sm text-decoration-none text-dark position-relative overflow-hidden rounded-4"
                       style="transition: transform 0.3s ease;">
                        {{-- صورة الفيديو --}}
                        <img src="{{ asset('storage/' . $video->thumbnail) }}" 
                             class="card-img-top" 
                             alt="{{ __('messages.video_thumbnail') }}" 
                             style="height: 180px; object-fit: cover;">

                        <div class="card-body d-flex flex-column">
                            {{-- اسم القناة مع الصورة --}}
                            <div class="d-flex align-items-center mb-3">
                                <a href="{{ route('channels.show', $video->channel->id) }}">
                                    <img src="{{ asset('storage/' . ($video->channel->image ?? 'default-avatar.png')) }}" 
                                         alt="{{ __('messages.channel_image') }}"
                                         class="rounded-circle me-3 border border-2 border-primary"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                </a>
                                <h6 class="mb-0 text-truncate fw-semibold" style="max-width: calc(100% - 50px);">
                                    {{ $video->channel->name }}
                                </h6>
                            </div>

                            {{-- وصف الفيديو --}}
                            <p class="card-text text-muted flex-grow-1" 
                               style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ Str::limit($video->description, 120) }}
                            </p>

                            {{-- زر عرض الفيديو --}}
                            <div class="mt-3 text-center">
                                <button type="button" class="btn btn-outline-primary btn-sm px-4 fw-semibold">
                                    {{ __('messages.watch_video') }}
                                </button>
                            </div>
                        </div>

                        {{-- تأثير رفع البطاقة عند المرور --}}
                        <style>
                            a.card:hover {
                                transform: translateY(-6px) scale(1.03);
                                box-shadow: 0 10px 20px rgba(0,0,0,0.12);
                                text-decoration: none;
                            }
                        </style>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $videos->links() }}
        </div>
    @else
        <p class="text-center text-muted fs-5">{{ __('messages.no_videos') }}</p>
    @endif
</div>
@endsection
