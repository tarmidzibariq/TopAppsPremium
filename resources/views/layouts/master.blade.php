<!doctype html>
<html lang="en" class="layout-menu-fixed layout-compact" data-assets-path="../assets/" data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', 'Dashboard') | Top Apps Premium</title>
    <meta name="description" content="" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets-adminTemplate/img/favicon/favicon.ico') }}" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets-adminTemplate/vendor/fonts/iconify-icons.css') }}" />
    <!-- Core & Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets-adminTemplate/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-adminTemplate/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-adminTemplate/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-adminTemplate/vendor/libs/apex-charts/apex-charts.css') }}" />
    <!-- Helpers & Config -->
    <script src="{{ asset('assets-adminTemplate/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets-adminTemplate/js/config.js') }}"></script>
  </head>
  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        @include('layouts.sidebar')
        <!-- Layout container -->
        <div class="layout-page">
          @include('layouts.navbar')
          <!-- Content wrapper -->
          @yield('content')
        </div>
      </div>
      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    @stack('page-scripts')
    <!-- Core & Vendors JS -->
    <script src="{{ asset('assets-adminTemplate/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets-adminTemplate/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets-adminTemplate/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets-adminTemplate/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets-adminTemplate/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets-adminTemplate/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <!-- Main JS -->
    <script src="{{ asset('assets-adminTemplate/js/main.js') }}"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>