@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="mx-auto bg-white p-4 p-md-5 rounded shadow-sm" style="max-width: 480px;">

        <h2 class="mb-4 fw-bold text-primary text-center">{{ __('messages.create_new_channel') }}</h2>

        <form action="{{ route('channels.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="mb-4">
                <label for="name" class="form-label fw-semibold">{{ __('messages.channel_name') }}</label>
                <input type="text" id="name" name="name" 
                       class="form-control form-control-lg border-primary @error('name') is-invalid @enderror" 
                       placeholder="{{ __('messages.enter_channel_name') }}" required autofocus
                       value="{{ old('name') }}">

                @error('name')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="form-label fw-semibold">{{ __('messages.description') }}</label>
                <textarea id="description" name="description" 
                          class="form-control border-primary @error('description') is-invalid @enderror" 
                          rows="4" placeholder="{{ __('messages.enter_description') }}">{{ old('description') }}</textarea>

                @error('description')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="image" class="form-label fw-semibold">{{ __('messages.channel_image') }}</label>
                <input type="file" id="image" name="image" 
                       class="form-control @error('image') is-invalid @enderror" accept="image/*">

                @error('image')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">
                {{ __('messages.create_channel') }}
            </button>
        </form>

    </div>
</div>
@endsection
