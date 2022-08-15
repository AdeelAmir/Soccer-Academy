<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @include('dashboard.layouts.partials.head')
    <style type="text/css">
        .table > tbody > tr > td {
            vertical-align: middle;
        }
    </style>
    <script type="text/javascript" rel="script">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="https://js.stripe.com/v3/"></script>

    <!-- Loader CSS -->
    <link rel="stylesheet" href="{{ asset('public/assets/css/loader.css') }}">
</head>
<body class="page-body">
<div class="page-container">
    <div class="main-content p-0">
        @yield('content')
    </div>
</div>
@include('dashboard.layouts.partials.footer-scripts')
@stack('extended-scripts')
</body>
</html>