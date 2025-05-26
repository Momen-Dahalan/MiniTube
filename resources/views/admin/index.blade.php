@extends('layouts.dashboard')

@section('title', __('messages.dashboard'))

@section('content')
<div class="row g-4">

    {{-- الفيديوهات --}}
    <div class="col-md-3">
        <div class="card shadow border-0 text-white bg-gradient-primary">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-uppercase">{{ __('messages.videos') }}</h6>
                    <h3 class="fw-bold">{{ $videosCount }}</h3>
                </div>
                <i class="fas fa-video fa-3x opacity-75"></i>
            </div>
        </div>
    </div>

    {{-- القنوات --}}
    <div class="col-md-3">
        <div class="card shadow border-0 text-white bg-gradient-success">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-uppercase">{{ __('messages.channels') }}</h6>
                    <h3 class="fw-bold">{{ $channelsCount }}</h3>
                </div>
                <i class="fas fa-tv fa-3x opacity-75"></i>
            </div>
        </div>
    </div>

    {{-- التصنيفات --}}
    <div class="col-md-3">
        <div class="card shadow border-0 text-white bg-gradient-warning">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-uppercase">{{ __('messages.categories') }}</h6>
                    <h3 class="fw-bold">{{ $categoriesCount }}</h3>
                </div>
                <i class="fas fa-tags fa-3x opacity-75"></i>
            </div>
        </div>
    </div>

    {{-- المستخدمين --}}
    <div class="col-md-3">
        <div class="card shadow border-0 text-white bg-gradient-danger">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-uppercase">{{ __('messages.users') }}</h6>
                    <h3 class="fw-bold">{{ $usersCount }}</h3>
                </div>
                <i class="fas fa-users fa-3x opacity-75"></i>
            </div>
        </div>
    </div>

</div>
@endsection
