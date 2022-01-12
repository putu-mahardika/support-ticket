<!DOCTYPE html>
<html>

<head>
    <title>{{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('theme/img/headset.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ url(mix('css/app.css')) }}">
    @yield('styles')
</head>

<body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden login-page">
    <div class="app flex-row align-items-center">
        <div class="container">
            @yield("content")
        </div>
    </div>

    <script src="{{ url(mix('js/app.js')) }}"></script>

</body>
</html>


