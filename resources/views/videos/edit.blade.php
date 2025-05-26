@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="mx-auto bg-white p-5 rounded-4 shadow" style="max-width: 720px;">

        {{-- العنوان --}}
        <h2 class="text-center mb-3 fw-bold text-primary">{{ __('messages.edit_video_title') }}</h2>
        <p class="text-center text-secondary mb-4">{{ __('messages.edit_video_subtitle') }}</p>

        <form action="{{ route('videos.update', $video) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            {{-- عنوان الفيديو --}}
            <div class="mb-4">
                <label for="title" class="form-label fw-semibold">{{ __('messages.video_title') }}</label>
                <input type="text" name="title" id="title" 
                       value="{{ old('title', $video->title) }}" 
                       class="form-control form-control-lg @error('title') is-invalid @enderror" required autofocus>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- وصف الفيديو --}}
            <div class="mb-4">
                <label for="description" class="form-label fw-semibold">{{ __('messages.video_description') }}</label>
                <textarea name="description" id="description" rows="4" 
                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $video->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- التصنيف --}}
            <div class="mb-4">
                <label for="category_id" class="form-label fw-semibold">{{ __('messages.video_category') }}</label>
                <select name="category_id" id="category_id" 
                        class="form-select @error('category_id') is-invalid @enderror">
                    <option value="">{{ __('messages.no_category') }}</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (old('category_id', $video->category_id) == $cat->id) ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- عرض الفيديو الحالي --}}
            @if($video->video_path)
                <div class="mb-4">
                    <label class="form-label fw-semibold">{{ __('messages.current_video') }}</label>
                    <a href="{{ Storage::disk('s3')->url($video->video_path) }}" target="_blank"
                       class="btn btn-success d-inline-flex align-items-center gap-2">
                        {{ __('messages.watch_current_video') }}
                    </a>
                </div>
            @endif

            {{-- رفع فيديو جديد --}}
            <div class="mb-4">
                <label for="video_path" class="form-label fw-semibold">{{ __('messages.upload_new_video') }}</label>
                <input type="file" name="video_path" id="video_path" 
                       accept="video/*" 
                       class="form-control @error('video_path') is-invalid @enderror" />
                @error('video_path')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- زر الحفظ --}}
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">
                    {{ __('messages.save_changes') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
