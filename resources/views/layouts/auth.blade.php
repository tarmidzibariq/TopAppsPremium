<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="layout-wide customizer-hide"
    data-assets-path="{{ asset('assets-adminTemplate') }}/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('Login')) — {{ config('app.name', 'Laravel') }}</title>

    <meta name="description" content="" />

    <link rel="icon" type="image/x-icon"
        href="{{ asset('assets-adminTemplate/img/favicon/favicon.ico') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets-adminTemplate/vendor/fonts/iconify-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-adminTemplate/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-adminTemplate/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-adminTemplate/vendor/css/pages/page-auth.css') }}" />

    <script src="{{ asset('assets-adminTemplate/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets-adminTemplate/js/config.js') }}"></script>

    @stack('styles')
</head>

<body>
    @yield('content')

    <script src="{{ asset('assets-adminTemplate/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets-adminTemplate/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets-adminTemplate/vendor/js/bootstrap.js') }}"></script>

    @stack('scripts')
</body>

</html>
