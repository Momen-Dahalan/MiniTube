@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="mx-auto bg-white p-4 p-md-5 rounded shadow-sm" style="max-width: 600px;">

        <h2 class="mb-4 fw-bold text-primary text-center">{{ __('messages.edit_channel') }}</h2>

        <form action="{{ route('channels.update', $channel) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            {{-- اسم القناة --}}
            <div class="mb-4">
                <label for="name" class="form-label fw-semibold">{{ __('messages.channel_name') }}</label>
                <input type="text" id="name" name="name" 
                       value="{{ old('name', $channel->name) }}" 
                       class="form-control form-control-lg border-primary @error('name') is-invalid @enderror"
                       required autofocus>
                @error('name')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- الوصف --}}
            <div class="mb-4">
                <label for="description" class="form-label fw-semibold">{{ __('messages.description') }}</label>
                <textarea id="description" name="description" rows="4" 
                          class="form-control border-primary @error('description') is-invalid @enderror">{{ old('description', $channel->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- صورة القناة الحالية --}}
            <div class="mb-4">
                <label class="form-label fw-semibold d-block">{{ __('messages.current_image') }}</label>
                @if($channel->image)
                    <img src="{{ asset('storage/' . $channel->image) }}" 
                         alt="صورة القناة" 
                         class="img-thumbnail mb-2" 
                         style="height: 120px; object-fit: cover;">
                @else
                    <p class="text-muted">{{ __('messages.no_image_available') }}</p>
                @endif
            </div>

            {{-- تحديث صورة القناة --}}
            <div class="mb-4">
                <label for="image" class="form-label fw-semibold">{{ __('messages.update_image') }}</label>
                <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                @error('image')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- زر الحفظ --}}
            <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">
                {{ __('messages.save_changes') }}
            </button>
        </form>
    </div>
</div>
@endsection
