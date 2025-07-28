<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $globalSetting?->store_name ?? 'اسم المتجر' }}</title>

    <!-- Google Font: Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin-lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- AdminLTE core -->
    <link rel="stylesheet" href="{{ asset('admin-lte/dist/css/adminlte.min.css') }}">

    <!-- RTL Styles -->
    <link rel="stylesheet" href="{{ asset('css/adminlte-rtl.css') }}">

    <!-- Custom Arabic Fonts + Body Direction -->
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            text-align: right;
        }

        .main-sidebar {
            right: 0 !important;
            left: auto !important;
        }

        body.sidebar-mini .content-wrapper {
            margin-right: 250px !important;
            margin-left: 0 !important;
        }


        [dir="rtl"] .ml-auto, [dir="rtl"] .mx-auto {
            margin-left: 0 !important;
            margin-right: auto !important;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

    @include('layouts.partials.navbar') <!-- اختياري إذا لديك ترويسة -->
    @include('layouts.partials.sidebar') <!-- القائمة الجانبية -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content pt-3 px-3">
            @yield('content')
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer text-center">
        <strong>جميع الحقوق محفوظة &copy; {{ date('Y') }}.</strong>
    </footer>
</div>
@stack('scripts')

<!-- Scripts -->
<script src="{{ asset('admin-lte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin-lte/dist/js/adminlte.min.js') }}"></script>

</body>
</html>
