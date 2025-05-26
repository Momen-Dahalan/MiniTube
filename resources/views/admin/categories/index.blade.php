@extends('layouts.dashboard')

@section('title', __('messages.View All Categories'))

@section('link', __('messages.View All Categories'))

@section('content')
{{-- رسائل السيشن --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('messages.Close') }}">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('messages.Close') }}">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.Categories List') }}</h3>
    </div>
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('messages.Category Name') }}</th>
                    <th>{{ __('messages.Created At') }}</th>
                    <th>{{ __('messages.Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->created_at }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-primary">
                            {{ __('messages.Edit') }}
                        </a>

                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('messages.Delete confirm') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                {{ __('messages.Delete') }}
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>{{ __('messages.Category Name') }}</th>
                    <th>{{ __('messages.Created At') }}</th>
                    <th>{{ __('messages.Actions') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
