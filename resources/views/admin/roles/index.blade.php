@extends('layouts.dashboard')

@section('title', __('messages.roles'))
@section('link', __('messages.manage_roles'))

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

<a href="{{ route('admin.roles.create') }}" class="btn btn-success mb-3">{{ __('messages.add_new_role') }}</a>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.roles_list') }}</h3>
    </div>
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('messages.role_name') }}</th>
                    <th>{{ __('messages.permissions') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            @if($role->permissions->isNotEmpty())
                                <ul class="mb-0 pl-3">
                                    @foreach($role->permissions as $permission)
                                        <li>{{ $permission->name }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">{{ __('messages.no_permissions') }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary btn-sm">{{ __('messages.edit') }}</a>
                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" style="display:inline-block" onsubmit="return confirm('{{ __('messages.delete_confirm') }}')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">{{ __('messages.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>{{ __('messages.role_name') }}</th>
                    <th>{{ __('messages.permissions') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
