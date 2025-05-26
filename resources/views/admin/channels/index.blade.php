@extends('layouts.dashboard')

@section('title', __('messages.View All Channels'))

@section('link', __('messages.View All Channels'))

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
        <h3 class="card-title">
            <i class="fas fa-broadcast-tower mr-2"></i> {{ __('messages.Channels List') }}
        </h3>
    </div>

    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>{{ __('messages.Channel Name') }}</th>
                    <th>{{ __('messages.Description') }}</th>
                    <th>{{ __('messages.Image') }}</th>
                    <th>{{ __('messages.Owner') }}</th>
                    <th>{{ __('messages.Created At') }}</th>
                    <th>{{ __('messages.Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($channels as $channel)
                <tr>
                    <td>{{ $channel->name }}</td>
                    <td>{{ Str::limit($channel->description, 50) }}</td>
                    <td>
                        @if($channel->image)
                            <img src="{{ asset('storage/' . $channel->image) }}" width="60" class="img-thumbnail" alt="channel image">
                        @else
                            <span class="text-muted">{{ __('messages.No Image') }}</span>
                        @endif
                    </td>
                    <td>{{ $channel->user->name ?? '—' }}</td>
                    <td>{{ $channel->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('admin.channels.edit', $channel) }}" class="btn btn-sm btn-primary">
                            {{ __('messages.Edit') }}
                        </a>

                        <form action="{{ route('admin.channels.destroy', $channel) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('messages.Confirm Delete Channel') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.Delete') }}</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>{{ __('messages.Channel Name') }}</th>
                    <th>{{ __('messages.Description') }}</th>
                    <th>{{ __('messages.Image') }}</th>
                    <th>{{ __('messages.Owner') }}</th>
                    <th>{{ __('messages.Created At') }}</th>
                    <th>{{ __('messages.Actions') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection
