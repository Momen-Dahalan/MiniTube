<!DOCTYPE html>
{{-- <html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur']) ? 'rtl' : 'ltr' }}"> --}}
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'MiniTube') }}</title>
    @vite('resources/js/app.js')

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet" />

    <style>
        .navbar-custom {
            background: linear-gradient(90deg, #4f46e5 0%, #3b82f6 100%);
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: white;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .navbar-custom .nav-link:hover {
            color: #facc15;
        }

        .avatar {
            width: 36px;
            height: 36px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #facc15;
            transition: transform 0.3s ease;
        }

        .avatar:hover {
            transform: scale(1.1);
        }

        .hover-shadow:hover {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            background-color: #e9ecef;
        }

        .alert-body {
            word-wrap: break-word;
            white-space: normal;
            max-width: 300px;
        }

        .alert-body .dropdown-item {
            white-space: normal;
            word-break: break-word;
        }
    </style>
    @yield('style')
    @livewireStyles
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fs-3" href="{{ route('home.index') }}">MiniTube</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse d-flex flex-column flex-lg-row @if (app()->getLocale() == 'ar') flex-row-reverse @else flex-row @endif"
                id="navbarContent">

                <!-- Left links -->
                <ul class="navbar-nav @if (app()->getLocale() == 'ar') ms-auto @else me-auto @endif mb-2 mb-lg-0">
                    @if (auth()->user() && auth()->user()->channel)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('channels.index') }}">
                                <i class="fas fa-tv me-1"></i> {{ __('messages.my_channel') }}
                            </a>
                        </li>
                    @elseif(auth()->check())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('channels.create') }}">
                                <i class="fas fa-plus-circle me-1"></i> {{ __('messages.create_channel') }}
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categories.index') }}">
                            <i class="fas fa-list-alt me-1"></i> {{ __('messages.categories') }}
                        </a>
                    </li>
                </ul>

                <!-- Right links -->
                {{-- <ul class="navbar-nav @if (app()->getLocale() == 'ar') me-auto @else ms-auto @endif align-items-center">
                @auth
                    <!-- Nav Item - Alerts -->
                    <li class="nav-item dropdown alert-dropdown">
                        <a class="nav-link position-relative" href="#" id="alertsDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-bell fa-fw fs-5"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notif-count"
                                data-count="0">
                                0
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in p-0"
                            aria-labelledby="alertsDropdown" style="width: 320px;">
                            <div
                                class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-light">
                                <span class="fw-bold">{{ __('messages.notifications') }}</span>
                                <button id="markAllRead" class="btn btn-sm btn-outline-primary">{{ __('messages.mark_all_read') }}</button>
                            </div>
                            <div class="alert-body p-2" style="max-width: 300px; white-space: normal;">
                                <span class="dropdown-item text-muted no-notifications">لا توجد إشعارات جديدة</span>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown ms-3">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4f46e5&color=fff&size=64"
                                alt="User Avatar" class="avatar shadow-sm me-2 rounded-circle" title="{{ Auth::user()->name }}" />
                            <span class="text-white fw-semibold">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user me-2 text-primary"></i> {{ __('messages.profile') }}
                                </a>
                            </li>
                            @can('view-dashboard')
                            <li>
                                <a class="dropdown-item" href="{{ url('/admin/dashboard') }}">
                                    <i class="fas fa-cogs me-2 text-success"></i> {{ __('messages.dashboard') }}
                                </a>
                            </li>  
                            @endcan

                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> {{ __('messages.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>


                    <form action="#" method="POST" class="ms-3">
                        @csrf
                        <select name="language" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>العربية</option>
                            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                        </select>
                    </form>

                @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-outline-light me-2">{{ __('messages.login') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="btn btn-warning text-dark fw-bold">{{ __('messages.register') }}</a>
                    </li>
                @endauth
            </ul> --}}

                <ul
                    class="navbar-nav @if (app()->getLocale() == 'ar') me-auto @else ms-auto @endif align-items-center">
                    @auth
                        <!-- الإشعارات -->
                        <li class="nav-item dropdown alert-dropdown">
                            <a class="nav-link position-relative" href="#" id="alertsDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-bell fa-fw fs-5"></i>
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notif-count"
                                    data-count="0">0</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in p-0"
                                aria-labelledby="alertsDropdown" style="width: 320px;">
                                <div
                                    class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-light">
                                    <span class="fw-bold">{{ __('messages.notifications') }}</span>
                                    <button id="markAllRead"
                                        class="btn btn-sm btn-outline-primary">{{ __('messages.mark_all_read') }}</button>
                                </div>
                                <div class="alert-body p-2" style="max-width: 300px; white-space: normal;">
                                    <span class="dropdown-item text-muted no-notifications">لا توجد إشعارات جديدة</span>
                                </div>
                            </div>
                        </li>

                        <!-- المستخدم -->
                        <li class="nav-item dropdown ms-3">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4f46e5&color=fff&size=64"
                                    alt="User Avatar" class="avatar shadow-sm me-2 rounded-circle"
                                    title="{{ Auth::user()->name }}" />
                                <span class="text-white fw-semibold">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-user me-2 text-primary"></i> {{ __('messages.profile') }}
                                    </a>
                                </li>
                                @can('view-dashboard')
                                    <li>
                                        <a class="dropdown-item" href="{{ url('/admin/dashboard') }}">
                                            <i class="fas fa-cogs me-2 text-success"></i> {{ __('messages.dashboard') }}
                                        </a>
                                    </li>
                                @endcan
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> {{ __('messages.logout') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>

                        <!-- اختيار اللغة -->
                        <li class="nav-item ms-3">
                            <form action="{{route('lang.chang')}}" method="POST">
                                @csrf
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white"><i class="fas fa-globe"></i></span>
                                    <select name="language" class="form-select border-0" onchange="this.form.submit()"
                                        style="min-width: 100px;">
                                        <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>العربية
                                        </option>
                                        <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English
                                        </option>
                                    </select>
                                </div>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}"
                                class="btn btn-outline-light me-2">{{ __('messages.login') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}"
                                class="btn btn-warning text-dark fw-bold">{{ __('messages.register') }}</a>
                        </li>
                    @endauth
                </ul>

            </div>
        </div>
    </nav>


    <!-- Flash Messages -->
    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                {{ __(session('success')) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                {{ __(session('error')) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
    <!-- Main Content -->
    <main class="container my-5">
        @yield('content')
    </main>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js" crossorigin="anonymous"></script>

    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts





    <script type="module">
        @if (Auth::check())
            var video_userId = {{ auth()->user()->id }};
            Echo.private('video-owner.' + video_userId)
                .listen('.CommentNotification', (data) => {
                    var notificationWrapper = $('.alert-dropdown');
                    var notificationToggle = notificationWrapper.find('a[data-bs-toggle]');
                    var notificationCountEle = notificationWrapper.find('.notif-count');
                    var notificationCount = parseInt(notificationCountEle.text()) || 0;
                    var notification = notificationWrapper.find('div.alert-body');
                    var noNotifications = notification.find('.no-notifications');

                    // توليد الإشعار الجديد
                    var newNotificationHtml = `
                        <div class="dropdown-item px-3 py-2 mb-1 rounded bg-light" style="border: 1px solid #dee2e6;">
                        <small class="text-muted ms-2" style="white-space: nowrap;">${data.date}</small>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-dark">
                                    <i class="fas fa-comment-dots text-primary me-2"></i>
                                    <span>${data.comment_user_name} وضع تعليقًا على <b>${data.video_title}</b></span>
                                </div>
                            </div>
                        </div>
                    `;

                    // إخفاء "لا توجد إشعارات"
                    noNotifications.hide();

                    // إضافته للأعلى
                    var existingNotification = notification.html();
                    notification.html(newNotificationHtml + existingNotification);

                    // تحديث العداد
                    notificationCount += 1;
                    notificationCountEle.text(notificationCount);
                    notificationWrapper.show();
                });
        @endif
    </script>





    <script type="module">
        @if (Auth::check())
            var video_userId = {{ auth()->user()->id }};
            Echo.private('video-like.' + video_userId)
                .listen('.LikeNotification', (data) => {
                    var notificationWrapper = $('.alert-dropdown');
                    var notificationToggle = notificationWrapper.find('a[data-bs-toggle]');
                    var notificationCountEle = notificationWrapper.find('.notif-count');
                    var notificationCount = parseInt(notificationCountEle.text()) || 0;
                    var notification = notificationWrapper.find('div.alert-body');
                    var noNotifications = notification.find('.no-notifications');

                    // توليد الإشعار الجديد
                    var newNotificationHtml = `
                        <div class="dropdown-item px-3 py-2 mb-1 rounded bg-light" style="border: 1px solid #dee2e6;">
                        <small class="text-muted ms-2" style="white-space: nowrap;">${data.date}</small>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-dark">
                                    <i class="fas fa-comment-dots text-primary me-2"></i>
                                    <span>${data.comment_user_name} وضع اعجابا على <b>${data.video_title}</b></span>
                                </div>
                            </div>
                        </div>
                    `;

                    // إخفاء "لا توجد إشعارات"
                    noNotifications.hide();

                    // إضافته للأعلى
                    var existingNotification = notification.html();
                    notification.html(newNotificationHtml + existingNotification);

                    // تحديث العداد
                    notificationCount += 1;
                    notificationCountEle.text(notificationCount);
                    notificationWrapper.show();
                });
        @endif
    </script>



    <script type="module">
        @if (Auth::check())
            var video_userId = {{ auth()->user()->id }};
            Echo.private('video-subscribe.' + video_userId)
                .listen('.SubscribeNotification', (data) => {
                    var notificationWrapper = $('.alert-dropdown');
                    var notificationToggle = notificationWrapper.find('a[data-bs-toggle]');
                    var notificationCountEle = notificationWrapper.find('.notif-count');
                    var notificationCount = parseInt(notificationCountEle.text()) || 0;
                    var notification = notificationWrapper.find('div.alert-body');
                    var noNotifications = notification.find('.no-notifications');

                    // توليد الإشعار الجديد
                    var newNotificationHtml = `
                        <div class="dropdown-item px-3 py-2 mb-1 rounded bg-light" style="border: 1px solid #dee2e6;">
                        <small class="text-muted ms-2" style="white-space: nowrap;">${data.date}</small>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-dark">
                                    <i class="fas fa-comment-dots text-primary me-2"></i>
                                    <span>${data.user_name}  اشترك في <b>قناتك</b></span>
                                </div>
                            </div>
                        </div>
                    `;

                    // إخفاء "لا توجد إشعارات"
                    noNotifications.hide();

                    // إضافته للأعلى
                    var existingNotification = notification.html();
                    notification.html(newNotificationHtml + existingNotification);

                    // تحديث العداد
                    notificationCount += 1;
                    notificationCountEle.text(notificationCount);
                    notificationWrapper.show();
                });
        @endif
    </script>





    <script type="module">
        @if (Auth::check())
            Pusher.logToConsole = true;
            Echo.private('user.notifications.{{ auth()->user()->id }}')
                .listen('.video.published', (data) => {
                    console.log('تم استقبال إشعار نشر فيديو:', data);
                    var notificationWrapper = $('.alert-dropdown');
                    var notificationToggle = notificationWrapper.find('a[data-bs-toggle]');
                    var notificationCountEle = notificationWrapper.find('.notif-count');
                    var notificationCount = parseInt(notificationCountEle.text()) || 0;
                    var notification = notificationWrapper.find('div.alert-body');
                    var noNotifications = notification.find('.no-notifications');

                    // توليد الإشعار الجديد
                    var newNotificationHtml = `
                        <div class="dropdown-item px-3 py-2 mb-1 rounded bg-light" style="border: 1px solid #dee2e6;">
                        <small class="text-muted ms-2" style="white-space: nowrap;">${data.date}</small>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-dark">
                                    <i class="fas fa-comment-dots text-primary me-2"></i>
                                    <span>قناة <b>${data.channel_name}</b> نشر/ ت فيديو جديد </span>
                                </div>
                            </div>
                        </div>
                    `;

                    // إخفاء "لا توجد إشعارات"
                    noNotifications.hide();

                    // إضافته للأعلى
                    var existingNotification = notification.html();
                    notification.html(newNotificationHtml + existingNotification);

                    // تحديث العداد
                    notificationCount += 1;
                    notificationCountEle.text(notificationCount);
                    notificationWrapper.show();
                });
        @endif
    </script>





    <script>
        $(document).ready(function() {
            // تأكد من أن المستخدم مسجّل دخول
            @if (Auth::check())
                $.ajax({
                    url: "{{ route('notification') }}",
                    type: "GET",
                    success: function(response) {
                        var notificationWrapper = $('.alert-dropdown');
                        var notificationCountEle = notificationWrapper.find('.notif-count');
                        var notification = notificationWrapper.find('div.alert-body');
                        var noNotifications = notification.find('.no-notifications');

                        // مسح الإشعارات الحالية
                        notification.html('');

                        if (response.someNotifications.length > 0) {
                            response.someNotifications.forEach(function(data) {

                                let message = '';
                                if (data.type === 'like') {
                                    message =
                                        `<a href="/videos/${data.video_id}#likes-section" style="text-decoration: none; color: inherit;">${data.comment_user_name} أعجب بـ <b>${data.video_title}</b></a>`;
                                } else if (data.type === 'comment') {
                                    message =
                                        `<a href="/videos/${data.video_id}" style="text-decoration: none; color: inherit; cursor: pointer;">${data.comment_user_name} وضع تعليقًا على <b>${data.video_title}</b></a>`;
                                } else if (data.type === 'subscribe') {
                                    message =
                                        `<a href="/channels/${data.channel_id}#subscribers" style="text-decoration: none; color: inherit; cursor: pointer;">${data.comment_user_name} اشترك في <b>قناتك</b></a>`;
                                } else if (data.type === 'publish_video') {
                                    message =
                                        `<a href="/videos/${data.video_id}" style="text-decoration: none; color: inherit;">قناة <b>${data.channel_name}</b> نشرت فيديو جديد</a>`;
                                } else {
                                    message = `لديك إشعار جديد على <b>${data.video_title}</b>`;
                                }

                                var html = `
                    <div class="dropdown-item px-3 py-2 mb-1 rounded bg-light" style="border: 1px solid #dee2e6;">
                        <small class="text-muted ms-2" style="white-space: nowrap;">${data.date}</small>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-dark">
                                <i class="fas fa-bell text-primary me-2"></i>
                                <span>${message}</span>
                            </div>
                        </div>
                    </div>
                `;
                                notification.append(html);
                            });

                            // تحديث العدد
                            notificationCountEle.text(response.someNotifications.length);
                            notificationCountEle.attr('data-count', response.someNotifications.length);
                        } else {
                            // في حال لا توجد إشعارات
                            notification.html(
                                '<span class="dropdown-item text-muted no-notifications">لا توجد إشعارات جديدة</span>'
                            );
                            notificationCountEle.text(0);
                            notificationCountEle.attr('data-count', 0);
                        }
                    },
                    error: function(xhr) {
                        console.error("حدث خطأ أثناء جلب الإشعارات.");
                    }
                });
            @endif
        });
    </script>







    <script>
        $('#markAllRead').on('click', function() {
            $.ajax({
                url: "{{ route('notification.markAllRead') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    $('.notif-count').text(0).attr('data-count', 0);
                    $('.alert-body').html(
                        '<span class="dropdown-item text-muted no-notifications">لا توجد إشعارات جديدة</span>'
                    );
                },
                error: function() {
                    console.error("حدث خطأ أثناء تمييز الإشعارات كمقروءة.");
                }
            });
        });
    </script>


</body>

</html>
