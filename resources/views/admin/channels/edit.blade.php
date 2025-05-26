@extends('layouts.dashboard')

@section('title', __('messages.Edit Channel'))

@section('link', __('messages.Edit Channel'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-primary shadow">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-broadcast-tower mr-2"></i>
                <h3 class="card-title mb-0">{{ __('messages.Edit Channel Info') }}</h3>
            </div>

            <form action="{{ route('admin.channels.update', $channel) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">

                    {{-- الاسم --}}
                    <div class="form-group">
                        <label for="name"><i class="fas fa-tag"></i> {{ __('messages.Channel Name') }}</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $channel->name) }}" required>
                        @error('name')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- الوصف --}}
                    <div class="form-group">
                        <label for="description"><i class="fas fa-align-left"></i> {{ __('messages.Channel Description') }}</label>
                        <textarea name="description" id="description" class="form-control" rows="4" placeholder="{{ __('messages.Channel About') }}">{{ old('description', $channel->description) }}</textarea>
                        @error('description')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- الصورة الحالية --}}
                    @if ($channel->image)
                        <div class="mb-3">
                            <label class="d-block mb-1"><strong>{{ __('messages.Current Image') }}:</strong></label>
                            <img src="{{ asset('storage/' . $channel->image) }}" class="img-thumbnail" width="100" alt="Channel Image">
                        </div>
                    @endif

                    {{-- صورة جديدة --}}
                    <div class="form-group">
                        <label for="image"><i class="fas fa-image"></i> {{ __('messages.New Channel Image') }} ({{ __('messages.Optional') }})</label>
                        <input type="file" name="image" id="image" class="form-control-file">
                        @error('image')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('admin.channels.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.Back') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('messages.Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
