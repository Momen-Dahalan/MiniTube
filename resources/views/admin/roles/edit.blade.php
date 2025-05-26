@extends('layouts.dashboard')

@section('title', __('messages.edit_role'))
@section('link', __('messages.edit_role_link', ['name' => $role->name]))

@section('content')
    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">{{ __('messages.role_name') }}</label>
            <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
        </div>

        <div class="form-group">
            <label>{{ __('messages.permissions') }}</label>
            <div class="row">
                @foreach($permissions as $permission)
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                class="form-check-input" id="perm_{{ $permission->id }}"
                                {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                            <label class="form-check-label" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
    </form>
@endsection
