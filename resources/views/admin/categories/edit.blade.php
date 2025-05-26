@extends('layouts.dashboard')

@section('title', __('messages.Edit Category'))
@section('link', __('messages.Edit Category'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-warning shadow">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-edit mr-2"></i>
                <h3 class="card-title mb-0">{{ __('messages.Edit Category') }}</h3>
            </div>

            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    {{-- الاسم بالعربية --}}
                    <div class="form-group">
                        <label for="name_ar">
                            <i class="fas fa-tag"></i> {{ __('messages.Name in Arabic') }}
                        </label>
                        <input type="text" name="name_ar" id="name_ar" class="form-control" value="{{ old('name_ar', $category->name_ar) }}" required>
                        @error('name_ar')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- الاسم بالإنجليزية --}}
                    <div class="form-group">
                        <label for="name_en">
                            <i class="fas fa-tag"></i> {{ __('messages.Name in English') }}
                        </label>
                        <input type="text" name="name_en" id="name_en" class="form-control" value="{{ old('name_en', $category->name_en) }}" required>
                        @error('name_en')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.Back') }}
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> {{ __('messages.Save changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
