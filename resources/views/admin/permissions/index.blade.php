@extends('layouts.dashboard')

@section('title', __('messages.permissions'))
@section('link', __('messages.manage_permissions'))

@section('content')

{{-- رسائل السيشن --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('messages.close') }}">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('messages.close') }}">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<a href="{{ route('admin.permissions.create') }}" class="btn btn-primary mb-3">{{ __('messages.add_new_permission') }}</a>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.permissions_list') }}</h3>
    </div>
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>{{ __('messages.id') }}</th>
                    <th>{{ __('messages.permission_name') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $permission)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $permission->name }}</td>
                        <td>
                            <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-sm btn-warning">{{ __('messages.edit') }}</a>

                            <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" style="display:inline-block" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>{{ __('messages.id') }}</th>
                    <th>{{ __('messages.permission_name') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
