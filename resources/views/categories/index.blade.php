@extends('layouts.main')

@section('content')
<div class="row">
    <!-- Sidebar -->
    <aside class="col-md-3 mb-4">
        <div class="bg-white border rounded p-3 sticky-top" style="top: 1rem;">
            <h2 class="h5 fw-bold mb-3 text-center border-bottom pb-2">{{ __('messages.categories') }}</h2>

            @if($categories->count())
                <ul class="list-group list-group-flush">
                    @foreach($categories as $cat)
                        <li class="list-group-item px-0 py-1 border-0">
                            <a href="?category_id={{ $cat->id }}"
                               class="d-block px-3 py-2 rounded text-decoration-none
                               {{ request('category_id') == $cat->id 
                                   ? 'bg-primary text-white' 
                                   : 'bg-light text-dark hover-shadow' }}">
                                {{ $cat->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted text-center">{{ __('messages.no_categories') }}</p>
            @endif
        </div>
    </aside>

    <!-- Main section -->
    <section class="col-md-9">
        <div class="bg-white border rounded p-4 shadow-sm">
            @if($category)
                <h2 class="h4 fw-bold mb-4 border-bottom pb-2">
                    {{ __('messages.videos_category', ['category' => $category->name]) }}
                </h2>

                @if($videos->count())
                    <div class="row g-4">
                        @foreach($videos as $video)
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                                    <a href="{{ route('videos.show', $video) }}">
                                        <img src="{{ asset('storage/thumnail/'.$video->thumbnail) }}"
                                             class="card-img-top"
                                             alt="Thumbnail for {{ $video->title }}"
                                             style="height: 200px; object-fit: cover;">
                                    </a>
                                    <div class="card-body">
                                        <h5 class="card-title mb-1">{{ $video->title }}</h5>
                                        <p class="card-text text-muted small mb-0">{{ __('messages.channel') }}: {{ $video->channel->name }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- روابط الباجينيشن -->
                    <div class="mt-4">
                        {{ $videos->withQueryString()->links() }}
                    </div>
                @else
                    <p class="text-muted">{{ __('messages.no_videos_in_category') }}</p>
                @endif
            @else
                <h2 class="h4 fw-bold mb-4">{{ __('messages.choose_category') }}</h2>
                <p class="text-muted">{{ __('messages.click_sidebar') }}</p>
            @endif
        </div>
    </section>
</div>
@endsection
