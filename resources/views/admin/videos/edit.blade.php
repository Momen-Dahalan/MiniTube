@extends('layouts.dashboard')

@section('title', __('messages.edit_video'))
@section('link', __('messages.edit_video'))

@section('content')
<div class="container py-5">
    <div class="mx-auto bg-dark text-white p-5 rounded-4 shadow" style="max-width: 720px;">

        <h2 class="text-center mb-3 fw-bold text-light">@lang('messages.edit_video')</h2>
        <p class="text-center text-secondary mb-4">@lang('messages.edit_video_subtitle')</p>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="@lang('messages.close')"></button>
            </div>
        @endif

        <form action="{{ route('videos.update', $video) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="form-label fw-semibold text-white">@lang('messages.video_title')</label>
                <input type="text" name="title" id="title"
                       value="{{ old('title', $video->title) }}"
                       class="form-control bg-secondary text-white border-0"
                       required autofocus>
                @error('title')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="form-label fw-semibold text-white">@lang('messages.video_description')</label>
                <textarea name="description" id="description" rows="4"
                          class="form-control bg-secondary text-white border-0">{{ old('description', $video->description) }}</textarea>
                @error('description')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="category_id" class="form-label fw-semibold text-white">@lang('messages.category_optional')</label>
                <select name="category_id" id="category_id" class="form-select bg-secondary text-white border-0">
                    <option value="" class="bg-dark">@lang('messages.no_category')</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" class="bg-dark text-white"
                                {{ (old('category_id', $video->category_id) == $cat->id) ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            @if($video->video_path)
                <div class="mb-4">
                    <label class="form-label fw-semibold text-white">@lang('messages.current_video')</label>
                    <a href="{{ Storage::disk('s3')->url($video->video_path) }}" target="_blank"
                       class="btn btn-outline-success d-inline-flex align-items-center gap-2">
                        @lang('messages.watch_current_video')
                    </a>
                </div>
            @endif

            <div class="mb-4">
                <label for="video_path" class="form-label fw-semibold text-white">@lang('messages.upload_new_video')</label>
                <input type="file" name="video_path" id="video_path" accept="video/*"
                       class="form-control bg-secondary text-white border-0" />
                @error('video_path')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg" onclick="this.disabled=true; this.form.submit();">
                    @lang('messages.save_changes')
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
