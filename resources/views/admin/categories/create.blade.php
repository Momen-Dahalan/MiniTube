@extends('layouts.dashboard')

@section('title', __('messages.Create Category'))

@section('link', __('messages.Create Category'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-success shadow">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-plus-circle mr-2"></i>
                <h3 class="card-title mb-0">{{ __('messages.Add a new category') }}</h3>
            </div>

            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    {{-- حقل الاسم --}}
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-tag"></i> {{ __('messages.Category name') }}
                        </label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="{{ __('messages.Example placeholder') }}" value="{{ old('name') }}" required>
                        @error('name')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.Back') }}
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle"></i> {{ __('messages.Save category') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
