@extends('layouts.dashboard')

@section('title', __('messages.add_permission'))

@section('content')
    <form action="{{ route('admin.permissions.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">{{ __('messages.permission_name') }}</label>
            <input type="text" name="name" class="form-control" placeholder="{{ __('messages.enter_permission_name') }}" required>
        </div>

        <button type="submit" class="btn btn-success mt-2">{{ __('messages.save') }}</button>
        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary mt-2">{{ __('messages.back') }}</a>
    </form>
@endsection
