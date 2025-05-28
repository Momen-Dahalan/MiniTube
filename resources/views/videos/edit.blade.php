@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="mx-auto bg-white p-4 p-md-5 rounded shadow-sm" style="max-width: 600px;">

        <h2 class="text-center mb-4 fw-bold text-primary">{{ __('messages.edit_video_title') }}</h2>

        <form action="{{ route('videos.update', $video->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            {{-- عنوان الفيديو --}}
            <div class="mb-4">
                <label for="title" class="form-label fw-semibold">
                    {{ __('messages.video_title') }} <span class="text-danger">*</span>
                </label>
                <input type="text" name="title" id="title" 
                       class="form-control form-control-lg @error('title') is-invalid @enderror" 
                       value="{{ old('title', $video->title) }}" required autofocus>
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
                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $video->description) }}</textarea>
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
                        <option value="{{ $cat->id }}" {{ old('category_id', $video->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- رفع صورة الثمنل (Thumbnail) مع معاينة --}}
            <div class="mb-4">
                <label for="thumbnail" class="form-label fw-semibold">
                    {{ __('messages.video_thumbnail') }}
                </label>
                <input type="file" name="thumbnail" id="thumbnail" 
                       accept="image/*"
                       class="form-control @error('thumbnail') is-invalid @enderror" />
                @error('thumbnail')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mt-3" style="width: 150px; height: 150px; border: 1px solid #ddd; border-radius: 5px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    <img id="thumbnail-preview" src="{{ asset('storage/thumbnails/' . $video->thumbnail) }}" alt="Thumbnail Preview" 
                         style="width: 100%; height: 100%; object-fit: cover;" />
                </div>
            </div>

            {{-- زر الإرسال --}}
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">
                    {{ __('messages.update_button') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('thumbnail').addEventListener('change', function(event) {
        const [file] = this.files;
        const preview = document.getElementById('thumbnail-preview');
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        } else {
            preview.src = '{{ asset("storage/thumbnails/" . $video->thumbnail) }}';
        }
    });
</script>
@endsection
 