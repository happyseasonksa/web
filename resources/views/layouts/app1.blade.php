<!DOCTYPE html>
<html dir="{{app()->getLocale() == 'ar'?'rtl':'ltr'}}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no"
    />
    <title>هابى سيزون</title>
    <link rel="shortcut icon" type="img/png" href="{{asset('dist/images/favicon.png')}}" />
    <link rel="stylesheet" href="{{asset('css/css/bootstrap.rtl.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/css/swiper-bundle.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/css/sal.css')}}" />
    <link rel="stylesheet" href="{{asset('css/css/main.css')}}" />
</head>

<body>

    @yield('content')

<script src="{{asset('js/js/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset('js/js/popper.min.js')}}"></script>
<script src="{{asset('js/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('js/js/swiper-bundle.min.js')}}"></script>
<script src="{{asset('js/js/sal.js')}}"></script>
<script src="{{asset('js/js/main.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
    @yield('script')
</body>
</html>
