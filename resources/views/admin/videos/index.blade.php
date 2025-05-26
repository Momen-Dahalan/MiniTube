@extends('layouts.dashboard')

@section('title', __('messages.view_all_videos'))

@section('link', __('messages.view_all_videos'))

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

<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.videos_list') }}</h3>
    </div>
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>{{ __('messages.title') }}</th>
                    <th>{{ __('messages.description') }}</th>
                    <th>{{ __('messages.channel') }}</th>
                    <th>{{ __('messages.category') }}</th>
                    <th>{{ __('messages.created_at') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($videos as $video)
                <tr>
                    <td>{{ $video->title }}</td>
                    <td>{{ Str::limit($video->description, 50) }}</td>
                    <td>{{ $video->channel->name ?? '—' }}</td>
                    <td>{{ $video->category->name ?? '—' }}</td>
                    <td>{{ $video->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('admin.videos.edit', $video) }}" class="btn btn-sm btn-primary">
                            {{ __('messages.edit') }}
                        </a>

                        <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('messages.confirm_delete') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                {{ __('messages.delete') }}
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>{{ __('messages.title') }}</th>
                    <th>{{ __('messages.description') }}</th>
                    <th>{{ __('messages.channel') }}</th>
                    <th>{{ __('messages.category') }}</th>
                    <th>{{ __('messages.created_at') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection
