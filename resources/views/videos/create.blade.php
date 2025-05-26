@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="mx-auto bg-white p-4 p-md-5 rounded shadow-sm" style="max-width: 600px;">

        <h2 class="text-center mb-4 fw-bold text-primary">{{ __('messages.add_video_title') }}</h2>

        <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            {{-- عنوان الفيديو --}}
            <div class="mb-4">
                <label for="title" class="form-label fw-semibold">
                    {{ __('messages.video_title') }} <span class="text-danger">*</span>
                </label>
                <input type="text" name="title" id="title" 
                       class="form-control form-control-lg @error('title') is-invalid @enderror" 
                       value="{{ old('title') }}" required autofocus>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- وصف الفيديو --}}
            <div class="mb-4">
                <label for="description" class="form-label fw-semibold">
                    {{ __('messages.video_description') }}
                </label>
                <textarea name="description" id="description" rows="4" 
                          class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- التصنيف --}}
            <div class="mb-4">
                <label for="category_id" class="form-label fw-semibold">
                    {{ __('messages.video_category') }}
                </label>
                <select name="category_id" id="category_id" 
                        class="form-select @error('category_id') is-invalid @enderror">
                    <option value="">{{ __('messages.no_category') }}</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- رفع الفيديو --}}
            <div class="mb-4">
                <label for="video_path" class="form-label fw-semibold">
                    {{ __('messages.video_file') }} <span class="text-danger">*</span>
                </label>
                <input type="file" name="video_path" id="video_path" 
                       accept="video/*" required 
                       class="form-control @error('video_path') is-invalid @enderror" />
                @error('video_path')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- زر الإرسال --}}
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">
                    {{ __('messages.upload_button') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
