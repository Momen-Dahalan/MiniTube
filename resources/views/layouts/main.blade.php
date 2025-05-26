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


    @yield('style')
    @livewireStyles
</head>

<body class="bg-light">


    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fs-3 fw-bold" href="{{ route('home.index') }}">MiniTube</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse mt-2 mt-lg-0" id="navbarContent">
                {{-- الروابط العلوية: إنشاء قناة، التصنيفات --}}
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @auth
                        @if (auth()->user()->channel)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('channels.index') }}">
                                    <i class="fas fa-tv me-1"></i> {{ __('messages.my_channel') }}
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('channels.create') }}">
                                    <i class="fas fa-plus-circle me-1"></i> {{ __('messages.create_channel') }}
                                </a>
                            </li>
                        @endif
                    @endauth

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categories.index') }}">
                            <i class="fas fa-list-alt me-1"></i> {{ __('messages.categories') }}
                        </a>
                    </li>
                </ul>

                {{-- الأدوات الجانبية: إشعارات، صورة، لغة --}}
                @auth
                    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center gap-3 ms-lg-3">

                        {{-- زر الإشعارات --}}
                        <div class="nav-item dropdown position-relative">
                            <a class="nav-link text-white" href="#" id="alertsDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="position-relative">
                                    <i class="fas fa-bell fa-lg"></i>
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notif-count"
                                        data-count="0">0</span>
                                </div>
                            </a>
                            <div class="dropdown-menu mt-2 shadow {{ app()->getLocale() == 'ar' ? 'dropdown-menu-end' : 'dropdown-menu-start' }}"
                                aria-labelledby="alertsDropdown" style="width: 320px;">
                                <div
                                    class="px-3 py-2 border-bottom bg-light d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">{{ __('messages.notifications') }}</span>
                                    <button id="markAllRead" class="btn btn-sm btn-outline-primary">
                                        {{ __('messages.mark_all_read') }}
                                    </button>
                                </div>
                                <div class="alert-body p-2">
                                    <span class="dropdown-item text-muted no-notifications">لا توجد إشعارات جديدة</span>
                                </div>
                            </div>
                        </div>

                        {{-- صورة المستخدم + القائمة --}}
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center text-white" href="#"
                                id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4f46e5&color=fff&size=64"
                                    alt="User Avatar" class="rounded-circle shadow me-2" width="35" height="35">
                                <span class="fw-semibold">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu shadow {{ app()->getLocale() == 'ar' ? 'dropdown-menu-end' : 'dropdown-menu-start' }}"
                                aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#"><i
                                            class="fas fa-user me-2 text-primary"></i>{{ __('messages.profile') }}</a></li>
                                @can('view-dashboard')
                                    <li><a class="dropdown-item" href="{{ url('/admin/dashboard') }}"><i
                                                class="fas fa-cogs me-2 text-success"></i>{{ __('messages.dashboard') }}</a>
                                    </li>
                                @endcan
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>{{ __('messages.logout') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>

                        {{-- اللغة --}}
                        <form action="{{ route('lang.chang') }}" method="POST" class="d-flex align-items-center">
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

                    </div>
                @else
                    {{-- للضيوف: تسجيل دخول وتسجيل جديد --}}
                    <div class="d-flex gap-2 mt-2 mt-lg-0">
                        <a href="{{ route('login') }}" class="btn btn-outline-light">{{ __('messages.login') }}</a>
                        <a href="{{ route('register') }}"
                            class="btn btn-warning text-dark fw-bold">{{ __('messages.register') }}</a>
                    </div>
                @endauth
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
