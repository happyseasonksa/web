<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name', 'Happy Seasons') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon Icon -->
    <link rel="icon" href="{{ asset('dist/img/happySeasonsLogo.jpeg') }}" type="image/gif" sizes="16x16">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Animate -->
    <link rel="stylesheet" href="{{ asset('dist/css/animate.min.css') }}">
    {{-- extra headers --}}
    @yield('header')
    {{-- extra headers END --}}
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Custom style Sheet -->
    <link href="{{ asset('css/custom.css') }}?v=4" rel="stylesheet">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <script>
        let baseUrl = "{{url('/')}}";
    </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        {{-- preloader --}}
        <div class="preloader">
            <img src="{{ asset('dist/img/happySeasonLoader.jpeg') }}" class="animate__animated animate__flip animate__infinite infinite" alt="">
        </div>
        {{-- preloader --}}
        {{-- nav bar --}}
        @include('layouts.nav')
        {{-- nav bar ENDS --}}

        {{-- sidebar --}}
        @include('layouts.sidebar')
        {{-- sidebar ENDS --}}

        <div class="content-wrapper">
            {{-- ajaxLoader --}}
            <div class="ajaxLoader" style="display: none;">
                <div class="spinner-box">
                  <div class="circle-border">
                    <div class="circle-core"></div>
                  </div>
                </div>
                {{-- <img src="{{ asset('dist/img/rsz_login_logo@3x.png') }}" class="animate__animated animate__flip animate__infinite infinite" alt=""> --}}
            </div>
            {{-- ajaxLoader --}}
            @include('layouts.message')
            @yield('content')
        </div>

        {{-- footer --}}
        @include('layouts.footer')
        {{-- footer ENDS --}}
    </div>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery -->
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <!-- Theme JS -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <!-- Select2 JS -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- CUSTOM JS -->
    <script src="{{ asset('js/custom.js') }}?v=1"></script>
    <!-- firebase JS -->
    <!-- Firebase -->
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-analytics.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-messaging.js"></script>
    <script src="{{ asset('js/firebase.js') }}"></script>

    @include('layouts.toastAlert')
    <script>
        $('[data-toggle="tooltip"]').tooltip();
        $(document).ready(function() {
            $('select.custom-select').select2();
        });

        $(window).on('load', function(){
            $('.preloader').fadeOut('slow');
        });

        $(document).ajaxSend(function(event,xhr,settings){
            if (settings.url) {
                var url = settings.url.split('?')[0];
                if (url !== "{{route('admin.getNotificationData')}}") {
                    $('.ajaxLoader').fadeIn('fast');
                }
            }
        });

        $(document).ajaxComplete(function(event,xhr,settings){
            if (settings.url) {
                var url = settings.url.split('?')[0];
                if (url !== "{{route('admin.getNotificationData')}}") {
                    $('.ajaxLoader').fadeOut('slow');
                    $('[data-toggle="tooltip"]').tooltip();
                }
            }
        });


        /* notification */
        let adminNotificationUpdated = false;
        function getNotificationData() {
            $.ajax({
                type:'GET',
                url:`{{route('admin.getNotificationData')}}`,
                data:{"_token": "{{ csrf_token() }}","updated":adminNotificationUpdated},
                success:function(data){
                    adminNotificationUpdated = true;
                    if(data.status){
                        storeNotifications(data.data);
                    } else errorResponse(data.message);
                },
                error: function(data){
                    adminNotificationUpdated = true;
                    if (data.status === 401) {
                        window.location.href = "{{url('/')}}";
                    }else if(data.status === 500){
                        errorResponse();
                    }
                }
            });
        }

        getNotificationData();

        // setInterval(function () {
        //     getNotificationData();
        // }, 3000);

        function storeNotifications(data) {
            if ($('#notification-container').children().length === 0) {
                let newContainer = `<span class="dropdown-item dropdown-header">0 {{__('Unread Notifications')}}</span>
                <div class="dropdown-divider"></div><a href="{{route('admin.getNotificationList')}}" class="dropdown-item dropdown-footer">{{__('See All Notifications')}}</a>`;
                $('#notification-container').append(newContainer);
            }
            if (data.total_unread) {
                $('#unread-badge').text(data.total_unread);
                $('#unread-badge').show();
            }else{
                $('#unread-badge').text(0);
                $('#unread-badge').hide();
            }
            if (data.notifications && data.notifications.length > 0) {
                let notiContainer = `<span class="dropdown-item dropdown-header">${data.total_unread} {{__('Unread Notifications')}}</span>
                <div class="dropdown-divider"></div>`;
                $.each(data.notifications, function( index, value ) {
                    let link='';
                    let type_txt='';
                    if (value.icon === "fas fa-user"){
                        link="{{route('admin.customer.index')}}";
                        type_txt='Customers'
                    }

                    notiContainer = notiContainer+`<a href="javascript:void(0)" onclick="getNotificationDetail(${value.id},this,'${link}','${type_txt}')" class="dropdown-item ${value.is_read == 1?'table-highlight':''}">
                        <span class='title'><i class="${value.icon} mr-2"></i> ${value.title} </span><span class="float-right text-muted text-sm">${value.recieved_at}</span>
                      </a><div class="dropdown-divider"></div>`;
                });
                notiContainer = notiContainer+`<a href="{{route('admin.getNotificationList')}}" class="dropdown-item dropdown-footer">{{__('See All Notifications')}}</a>`;
                $('#notification-container').empty();
                $('#notification-container').append(notiContainer);
            }
        }

        function getNotificationDetail(id,input,link='',type='') {
            $.ajax({
                type:'GET',
                url:`{{route('admin.getNotificationDetail')}}`,
                data:{"_token": "{{ csrf_token() }}","notification_id":id},
                success:function(data){
                    if(data.status){
                        if ($(input)) {
                            if ($(input).parent() && $(input).parent().prop("tagName") == 'TD') {
                                if ($(input).parent().parent()) {
                                    $(input).parent().parent().addClass('table-highlight')
                                }
                            }else{
                                $(input).addClass('table-highlight');
                            }
                        }
                        if (link !== '') {
                            Swal.fire({
                              title: data.data.title,
                              text: data.data.body,
                              icon: 'info',
                              showCancelButton: true,
                              confirmButtonColor: '#d33',
                              cancelButtonColor: '#3085d6',
                              confirmButtonText: 'Go to '+type,
                              cancelButtonText: 'Ok',
                            }).then((result) => {
                              if (result.isConfirmed) {
                                window.location.href = link;
                              }
                            })
                        }else{
                            Swal.fire({
                              icon: 'info',
                              title: data.data.title,
                              text: data.data.body,
                            });
                        }
                    } else errorResponse(data.message);
                },
                error: function(data){
                    errorResponse();
                }
            });
        }
    </script>
    {{-- extra footer --}}
    @yield('js-body')
    {{-- extra footer END --}}
    <script>
        $('a[data-widget="pushmenu"]').click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            $("body").toggleClass("sidebar-collapse");
            if ($(window).width() <= 991) {
                $("#sidebar-overlay").fadeToggle();
            }
        });

        $( "body" ).on( "click", "#sidebar-overlay", function(e) {
            e.preventDefault();
            e.stopPropagation();
            $("body").addClass("sidebar-collapse");
            $("#sidebar-overlay").fadeOut();
        });
    </script>
</body>
</html>
