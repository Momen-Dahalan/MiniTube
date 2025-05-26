@extends('layouts.dashboard')

@section('title', __('messages.edit_permission'))

@section('content')
    <form action="{{ route('admin.permissions.update', $permission) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">{{ __('messages.permission_name') }}</label>
            <input type="text" name="name" class="form-control" value="{{ $permission->name }}" required>
        </div>

        <button type="submit" class="btn btn-primary mt-2">{{ __('messages.update') }}</button>
        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary mt-2">{{ __('messages.back') }}</a>
    </form>
@endsection
